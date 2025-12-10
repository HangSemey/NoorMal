<?php

namespace App\Services;

/**
 * Practical Faraid calculator.
 *
 * INPUT:
 *  - $estateAmount (float)
 *  - $heirs: array of ['relationship' => 'son'|'daughter'|'wife'|'husband'|'mother'|'father'|'brother'|'sister', 'quantity' => int]
 *
 * OUTPUT:
 *  - array of shares keyed by relationship and index with absolute and pct values.
 *
 * NOTE: This is a pragmatic engine covering the common cases.
 *       It performs:
 *         1) fixed shares calculation,
 *         2) normalization if fixed shares > estate,
 *         3) residuary distribution for sons/daughters (sons 2x daughters),
 *            father as residuary if children absent in many cases,
 *            siblings may be residuary in childless cases.
 *
 * Validate results with a scholar before using for production.
 */
class FaraidCalculator
{
    protected $estate;
    protected $heirs;

    public function __construct(float $estateAmount, array $heirs)
    {
        $this->estate = $estateAmount;
        $this->heirs = $this->normalizeHeirs($heirs);
    }

    protected function normalizeHeirs($heirs)
    {
        // Convert to map: relationship => quantity
        $map = [];
        foreach ($heirs as $h) {
            $rel = strtolower($h['relationship']);
            $qty = max(0, intval($h['quantity'] ?? 1));
            if ($qty === 0) continue;
            if (!isset($map[$rel])) $map[$rel] = 0;
            $map[$rel] += $qty;
        }
        return $map;
    }

    public function calculate()
    {
        $estate = $this->estate;
        $h = $this->heirs;

        // 1) Calculate fixed shares (fractions)
        // Fractions are expressed as numerator/denominator.
        $fixed = []; // relation => fraction or function-of-present-heirs

        // Spouse share:
        // - If deceased is MALE => spouse = wife(s)
        //   Wife: 1/8 if children exist, else 1/4 (split among wives)
        // - If deceased is FEMALE => spouse = husband
        //   Husband: 1/4 if children exist, else 1/2
        // We'll detect spouse presence by keys 'wife' or 'husband'

        $hasChildren = ( (isset($h['son']) && $h['son']>0) || (isset($h['daughter']) && $h['daughter']>0) );

        if (isset($h['wife'])) {
            $den = $hasChildren ? 8 : 4;
            $fixed['wife'] = ['numerator' => 1, 'denominator' => $den, 'split_count' => $h['wife']];
        }

        if (isset($h['husband'])) {
            $den = $hasChildren ? 4 : 2;
            $fixed['husband'] = ['numerator' => 1, 'denominator' => $den, 'split_count' => $h['husband']];
        }

        // Parents:
        // Mother: 1/6 if children exist OR there are two or more siblings -- simplified: give 1/6 if children exist or if multiple siblings present.
        // Father: 1/6 in presence of children (and may become residuary otherwise).
        if (isset($h['mother'])) {
            // Use 1/6 when children exist OR there is more than 1 sibling. For practicality, apply 1/6 if children exist; otherwise also 1/6 if father absent and no children? We'll implement standard: mother gets 1/6 if children exist; if no children, mother may get 1/3 (in some cases). For safety, if no children and only mother present, set mother to 1/3.
            if ($hasChildren) {
                $fixed['mother'] = ['numerator' => 1, 'denominator' => 6, 'split_count' => $h['mother']];
            } else {
                // If no children:
                $fixed['mother'] = ['numerator' => 1, 'denominator' => 3, 'split_count' => $h['mother']];
            }
        }

        if (isset($h['father'])) {
            // If children present father often gets residuary (i.e., remainder after fixed shares) but many schools give father 1/6 if children exist.
            if ($hasChildren) {
                $fixed['father'] = ['numerator' => 1, 'denominator' => 6, 'split_count' => $h['father']];
            } else {
                // If no children father often becomes residuary (i.e., remainder). We'll give father 1/2 if only spouse+father? That's complicated.
                // For practicality: do not set fixed father when no children; he'll be residuary later.
            }
        }

        // Daughters and sons:
        // - If only one daughter and no son => daughter gets 1/2
        // - If two or more daughters and no son => they share 2/3
        // - If sons exist, daughters are residuary with ratio son=2 parts, daughter=1 part
        if (isset($h['daughter']) && !isset($h['son'])) {
            if ($h['daughter'] == 1) {
                $fixed['daughter'] = ['numerator'=>1,'denominator'=>2,'split_count'=>1];
            } else {
                $fixed['daughter'] = ['numerator'=>2,'denominator'=>3,'split_count'=>$h['daughter']];
            }
        }

        // Sons have no fixed share (except via residuary).
        // Siblings may have rights if no parents/children present; we'll handle residuary siblings later.

        // 2) Convert fixed fractions into absolute amounts and sum
        $fixedAmounts = [];
        $fixedTotal = 0.0;
        foreach ($fixed as $rel => $frac) {
            $num = $frac['numerator'];
            $den = $frac['denominator'];
            $splitCount = $frac['split_count'] ?? 1;
            $shareOfEstate = ($num / $den) * $estate;
            // each individual receives shareOfEstate / splitCount
            $perPerson = $shareOfEstate / max(1, $splitCount);
            $fixedAmounts[$rel] = [
                'per_person' => round($perPerson,2),
                'total' => round($shareOfEstate,2),
                'fraction' => "{$num}/{$den}",
                'count' => $splitCount
            ];
            $fixedTotal += $shareOfEstate;
        }

        // 3) If fixedTotal > estate, normalize fractions proportionally (ta'dil).
        if ($fixedTotal > $estate + 0.0001) {
            $scale = $estate / $fixedTotal;
            foreach ($fixedAmounts as $rel => $info) {
                $fixedAmounts[$rel]['total'] = round($info['total'] * $scale,2);
                $fixedAmounts[$rel]['per_person'] = round($fixedAmounts[$rel]['total'] / $info['count'],2);
            }
            $fixedTotal = $estate;
        }

        // 4) Remaining estate (residue) to distribute to residuary heirs:
        $residue = round($estate - $fixedTotal,2);

        $residuaries = []; // list of residuary types in order of priority
        // Priority: sons/daughters (children) are primary residuaries; father may be residuary if no children or in some cases even when children exist get fixed 1/6 + residuary? For simplicity we treat:
        // - If there is at least one son or daughter: they take the residue with ratio son: daughter = 2:1.
        // - Else if no children and father exists: father takes residue (often with mother having fixed)
        // - Else siblings (brother/sister) as residuary.
        if ($residue > 0.001) {
            if (isset($h['son']) || isset($h['daughter'])) {
                $sons = $h['son'] ?? 0;
                $daughters = $h['daughter'] ?? 0;
                $parts = ($sons * 2) + ($daughters * 1);
                if ($parts > 0) {
                    $perPart = $residue / $parts;
                    if ($sons>0) {
                        $residuaries['son'] = [
                            'per_person' => round($perPart * 2,2),
                            'total' => round($perPart * 2 * $sons,2),
                            'count' => $sons
                        ];
                    }
                    if ($daughters>0) {
                        $residuaries['daughter'] = [
                            'per_person' => round($perPart * 1,2),
                            'total' => round($perPart * 1 * $daughters,2),
                            'count' => $daughters
                        ];
                    }
                    $residue = 0.0;
                }
            } else {
                // no children
                if (isset($h['father'])) {
                    // father takes residue (he is residuary)
                    $residuaries['father'] = [
                        'per_person' => round($residue / $h['father'],2),
                        'total' => round($residue,2),
                        'count' => $h['father']
                    ];
                    $residue = 0.0;
                } else {
                    // siblings: brothers and sisters as residuaries with 2:1 ratio
                    $bro = $h['brother'] ?? 0;
                    $sis = $h['sister'] ?? 0;
                    if ($bro + $sis > 0) {
                        $parts = ($bro * 2) + ($sis * 1);
                        $perPart = $residue / $parts;
                        if ($bro>0) {
                            $residuaries['brother'] = [
                                'per_person' => round($perPart * 2,2),
                                'total' => round($perPart * 2 * $bro,2),
                                'count' => $bro
                            ];
                        }
                        if ($sis>0) {
                            $residuaries['sister'] = [
                                'per_person' => round($perPart * 1,2),
                                'total' => round($perPart * 1 * $sis,2),
                                'count' => $sis
                            ];
                        }
                        $residue = 0.0;
                    } else {
                        // leftover (rare) — keep as unassigned (maybe debts/will)
                    }
                }
            }
        }

        // 5) Build final breakdown
        $result = [
            'estate' => round($estate,2),
            'fixed' => $fixedAmounts,
            'residue_allocated' => $residuaries,
            'unallocated' => round($residue,2)
        ];

        return $result;
    }
}
