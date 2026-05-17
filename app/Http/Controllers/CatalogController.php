<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::where('status', 'tersedia');

        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price-asc':
                    $query->orderBy('harga_sewa', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('harga_sewa', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->latest();
        }

        $cars = $query->paginate(12);
        
        return view('catalog.index', compact('cars'));
    }

    /**
     * Tampilkan detail mobil
     */
    public function show($id)
    {
        $car = Car::findOrFail($id);
        $relatedCars = Car::where('kategori_id', $car->kategori_id)
                          ->where('id', '!=', $id)
                          ->take(4)
                          ->get();

        return view('catalog.show', compact('car', 'relatedCars'));
    }


}
