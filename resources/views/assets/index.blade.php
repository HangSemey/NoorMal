<h2>Add Asset</h2>

<form method="POST" action="/assets">
    @csrf
    <select name="type">
        <option value="tanah">Tanah</option>
        <option value="rumah">Rumah</option>
        <option value="kenderaan">Kenderaan</option>
        <option value="simpanan">Simpanan</option>
        <option value="lain">Lain-lain</option>
    </select>

    <input type="number" name="value" placeholder="Value" required>

    <button type="submit">Add Asset</button>
</form>

<hr>

<h2>Your Assets</h2>
@foreach($assets as $a)
    <p>{{ $a->type }} — RM {{ $a->value }}</p>
@endforeach
