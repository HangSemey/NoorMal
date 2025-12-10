class FaraidController extends Controller
{
    public function calculate() {

        $totalAssets = Asset::where('user_id', auth()->id())->sum('value');
        $heirs = Heir::where('user_id', auth()->id())->get();

        $shares = $this->applyFaraidRules($totalAssets, $heirs);

        return view('calculate.result', [
            'total' => $totalAssets,
            'heirs' => $heirs,
            'shares' => $shares
        ]);
    }

    private function applyFaraidRules($total, $heirs)
{
    $shares = [];

    foreach ($heirs as $heir) {

        if ($heir->relationship == 'wife') {
            $shares['wife'] = $total * (1/8);
        }

        if ($heir->relationship == 'husband') {
            $shares['husband'] = $total * (1/4);
        }

        if ($heir->relationship == 'mother') {
            $shares['mother'] = $total * (1/6);
        }

        if ($heir->relationship == 'father') {
            $shares['father'] = $total * (1/6);
        }

        if ($heir->relationship == 'son') {
            // calculate later with residuary rule
        }

        if ($heir->relationship == 'daughter') {
            // daughter gets 1/2 without son, residuary with son
        }
    }

    return $shares;
}

