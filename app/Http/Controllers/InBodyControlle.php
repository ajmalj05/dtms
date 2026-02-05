

public function apiStore(Request $request)
{
    $validated = $request->validate([
        'uhid' => 'required|string',
        'height' => 'required|numeric',
        'weight' => 'required|numeric',
        'bmi' => 'required|numeric',
        'body_mass' => 'required|string',
        'muscle_strength' => 'required|string',
    ]);

    // Save data to your EMR database
    InBody::create($validated);

    return response()->json(['message' => 'Data successfully saved to EMR'], 200);
}
