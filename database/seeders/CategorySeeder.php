<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electrical', 'description' => 'Electrical items and components'],
            ['name' => 'Furnitures', 'description' => 'Furniture and related materials'],
            ['name' => 'IT', 'description' => 'Information Technology equipment and accessories'],
            ['name' => 'Plumbing', 'description' => 'Plumbing materials and fittings'],
            ['name' => 'Civil', 'description' => 'Civil construction materials'],
            ['name' => 'Services', 'description' => 'Various services'],
            ['name' => 'ARC', 'description' => 'ARC related items'],
            ['name' => 'Others', 'description' => 'Other miscellaneous items'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $category = Category::create($cat);
            $categoryIds[$category->name] = $category->id;
        }

        $subcategories = [
            // Electrical subcategories
            ['category_id' => $categoryIds['Electrical'], 'name' => 'Switches / Plugs', 'description' => 'Electrical switches and plug components'],
            ['category_id' => $categoryIds['Electrical'], 'name' => 'MCB', 'description' => 'Miniature Circuit Breakers'],
            ['category_id' => $categoryIds['Electrical'], 'name' => 'Cables', 'description' => 'Electrical cables and wires'],
            ['category_id' => $categoryIds['Electrical'], 'name' => 'Capacitors', 'description' => 'Electrical capacitors'],
            ['category_id' => $categoryIds['Electrical'], 'name' => 'Fans', 'description' => 'Electrical fans and cooling equipment'],
            ['category_id' => $categoryIds['Electrical'], 'name' => 'Lights', 'description' => 'Lighting fixtures and bulbs'],
            ['category_id' => $categoryIds['Electrical'], 'name' => 'M&R', 'description' => 'Maintenance and Repair items'],
            ['category_id' => $categoryIds['Electrical'], 'name' => 'Other Items', 'description' => 'Other electrical items'],

            // Furnitures subcategories
            ['category_id' => $categoryIds['Furnitures'], 'name' => 'Plywood', 'description' => 'Plywood sheets and boards'],
            ['category_id' => $categoryIds['Furnitures'], 'name' => 'Laminates', 'description' => 'Laminated sheets and materials'],
            ['category_id' => $categoryIds['Furnitures'], 'name' => 'Fevicol', 'description' => 'Adhesives and glues'],
            ['category_id' => $categoryIds['Furnitures'], 'name' => 'Glass', 'description' => 'Glass materials and sheets'],
            ['category_id' => $categoryIds['Furnitures'], 'name' => 'M&R', 'description' => 'Maintenance and Repair items'],
            ['category_id' => $categoryIds['Furnitures'], 'name' => 'Other Items', 'description' => 'Other furniture items'],

            // IT subcategories
            ['category_id' => $categoryIds['IT'], 'name' => 'Computers', 'description' => 'Desktop computers and workstations'],
            ['category_id' => $categoryIds['IT'], 'name' => 'Printers', 'description' => 'Printers and printing equipment'],
            ['category_id' => $categoryIds['IT'], 'name' => 'Laptops', 'description' => 'Laptop computers and accessories'],
            ['category_id' => $categoryIds['IT'], 'name' => 'Network Switches', 'description' => 'Network switches and networking equipment'],
            ['category_id' => $categoryIds['IT'], 'name' => 'Keyboard', 'description' => 'Computer keyboards'],
            ['category_id' => $categoryIds['IT'], 'name' => 'Mouse', 'description' => 'Computer mice and pointing devices'],
            ['category_id' => $categoryIds['IT'], 'name' => 'Cables', 'description' => 'Computer cables and connectors'],
            ['category_id' => $categoryIds['IT'], 'name' => 'Hard Disk', 'description' => 'Hard disk drives and storage devices'],
            ['category_id' => $categoryIds['IT'], 'name' => 'RAM', 'description' => 'Random Access Memory modules'],
            ['category_id' => $categoryIds['IT'], 'name' => 'M&R', 'description' => 'Maintenance and Repair items'],
            ['category_id' => $categoryIds['IT'], 'name' => 'Other Items', 'description' => 'Other IT items'],

            // Plumbing subcategories
            ['category_id' => $categoryIds['Plumbing'], 'name' => 'Pipes', 'description' => 'Plumbing pipes and fittings'],
            ['category_id' => $categoryIds['Plumbing'], 'name' => 'Tapes', 'description' => 'Plumbing tapes and sealants'],
            ['category_id' => $categoryIds['Plumbing'], 'name' => 'M&R', 'description' => 'Maintenance and Repair items'],
            ['category_id' => $categoryIds['Plumbing'], 'name' => 'Other Items', 'description' => 'Other plumbing items'],

            // Civil subcategories
            ['category_id' => $categoryIds['Civil'], 'name' => 'Sand', 'description' => 'Construction sand and aggregates'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Cement', 'description' => 'Cement and concrete materials'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Steel', 'description' => 'Steel bars and structural steel'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Adhesives', 'description' => 'Construction adhesives and sealants'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Tiles', 'description' => 'Floor and wall tiles'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Graynites', 'description' => 'Granite and stone materials'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Other Items', 'description' => 'Other civil construction items'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Paint', 'description' => 'Paints and coatings'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Putty', 'description' => 'Wall putty and fillers'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Fabrication', 'description' => 'Fabrication materials and services'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'M&R', 'description' => 'Maintenance and Repair items'],
            ['category_id' => $categoryIds['Civil'], 'name' => 'Other Items', 'description' => 'Other civil items'],

            // Services subcategories
            ['category_id' => $categoryIds['Services'], 'name' => 'Services', 'description' => 'Various services'],

            // ARC subcategories
            ['category_id' => $categoryIds['ARC'], 'name' => 'ARC', 'description' => 'ARC related items'],

            // Others subcategories
            ['category_id' => $categoryIds['Others'], 'name' => 'Others', 'description' => 'Other miscellaneous items'],
        ];

        foreach ($subcategories as $subcat) {
            Subcategory::create($subcat);
        }
    }
} 