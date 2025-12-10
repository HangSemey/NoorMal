class AssetController extends Controller
{
    public function index() {
        $assets = Asset::where('user_id', auth()->id())->get();
        return view('assets.index', compact('assets'));
    }

    public function store(Request $request) {
        $request->validate([
            'type' => 'required',
            'value' => 'required|numeric'
        ]);

        Asset::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'value' => $request->value,
            'description' => $request->description
        ]);

        return back()->with('success', 'Asset added.');
    }
}
