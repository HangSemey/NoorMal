class HeirController extends Controller
{
    public function index(){
        $heirs = Heir::where('user_id', auth()->id())->get();
        return view('heirs.index', compact('heirs'));
    }

    public function store(Request $request) {
        $request->validate([
            'relationship' => 'required',
            'quantity' => 'required|integer'
        ]);

        Heir::create([
            'user_id' => auth()->id(),
            'relationship' => $request->relationship,
            'gender' => $request->gender,
            'quantity' => $request->quantity
        ]);

        return back()->with('success', 'Heir added.');
    }
}
