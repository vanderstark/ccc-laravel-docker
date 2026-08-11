<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimulationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'disaster_type' => ['required', 'string', 'exists:disaster_types,code'],
            'preset_id' => ['nullable', 'integer', 'exists:presets,id'],
            'war_id' => ['nullable', 'integer', 'exists:wars,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lon' => ['nullable', 'numeric', 'between:-180,180'],
            'population' => ['nullable', 'integer', 'min:1', 'max:50000000'],
            'area_km2' => ['nullable', 'numeric', 'min:0.1', 'max:500000'],
            'area_type' => ['nullable', 'string', 'in:urban,suburb,rural'],
            'infrastructure_density' => ['nullable', 'numeric', 'between:0,1'],
            'severity_scale' => ['nullable', 'numeric', 'between:0,1'],

            // Parameter khusus per tipe (opsional)
            'earthquake_magnitude' => ['nullable', 'numeric', 'between:1,10'],
            'earthquake_depth_km' => ['nullable', 'numeric', 'min:0'],
            'epicenter_distance_km' => ['nullable', 'numeric', 'min:0'],
            'tsunami_wave_height_m' => ['nullable', 'numeric', 'min:0'],
            'tsunami_epicenter_distance_km' => ['nullable', 'numeric', 'min:0'],
            'volcano_vei' => ['nullable', 'integer', 'between:0,8'],
            'volcano_eruption_distance_km' => ['nullable', 'numeric', 'min:0'],
            'flood_depth_m' => ['nullable', 'numeric', 'min:0'],
            'flood_duration_hours' => ['nullable', 'numeric', 'min:0'],
            'fire_area_ha' => ['nullable', 'numeric', 'min:0'],
            'fire_wind_speed_kmh' => ['nullable', 'numeric', 'min:0'],
            'fire_fuel_type' => ['nullable', 'string', 'in:peat,forest,mineral,urban'],
            'conflict_intensity' => ['nullable', 'numeric', 'between:0,1'],
            'conflict_type' => ['nullable', 'string'],
            'maritime_threat_level' => ['nullable', 'numeric', 'between:0,1'],
            'enemy_naval_units' => ['nullable', 'integer', 'min:0'],
            'air_threat_level' => ['nullable', 'numeric', 'between:0,1'],
            'enemy_aircraft' => ['nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'disaster_type.required' => 'Tipe bencana wajib dipilih.',
            'disaster_type.exists' => 'Tipe bencana tidak valid.',
        ];
    }
}