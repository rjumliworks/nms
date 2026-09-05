<?php

namespace Database\Seeders;

use App\Models\FishSpecies;
use App\Models\ListDropdown;
use Illuminate\Database\Seeder;

class FishSpeciesSeeder extends Seeder
{
    /**
     * Source: 2018 Tausug-English Dictionary: Kabtangan Iban Maana,
     * "Fish and Swimming Sea Creatures" appendix (SIL International).
     * Tausug name is left null where the dictionary has no documented
     * equivalent, rather than guessing. Categories are a market/taxonomic
     * grouping (not from the dictionary) meant as a sensible starting
     * point — rename or reassign anytime via the Fish Species form.
     */
    public function run(): void
    {
        $fish = [
            ['name' => 'Bangus', 'english_name' => 'Milkfish', 'scientific_name' => 'Chanos chanos', 'tausug_name' => 'Bangus', 'category' => 'Freshwater & Brackish Fish'],
            ['name' => 'Pampano', 'english_name' => 'Pompano / Cavalla', 'scientific_name' => 'Carangidae', 'tausug_name' => 'Anak bung', 'category' => 'Scad & Trevally'],
            ['name' => 'Alumahan', 'english_name' => 'Indian Mackerel', 'scientific_name' => 'Rastrelliger kanagurta', 'tausug_name' => 'Lumahan', 'category' => 'Tuna & Mackerel'],
            ['name' => 'Hasa-hasa', 'english_name' => 'Mackerel', 'scientific_name' => 'Rastrelliger spp.', 'tausug_name' => 'Lumahan', 'category' => 'Tuna & Mackerel'],
            ['name' => 'Tamban', 'english_name' => 'Indian Sardine', 'scientific_name' => 'Sardinella spp.', 'tausug_name' => 'Tamban', 'category' => 'Sardine & Anchovy'],
            ['name' => 'Sardinella', 'english_name' => 'Sardine', 'scientific_name' => 'Sardinella spp.', 'tausug_name' => 'Kasig', 'category' => 'Sardine & Anchovy'],
            ['name' => 'Galunggong', 'english_name' => 'Round Scad', 'scientific_name' => 'Decapterus spp.', 'tausug_name' => 'Tamarung', 'category' => 'Scad & Trevally'],
            ['name' => 'Matang-baka', 'english_name' => 'Big-eyed Scad', 'scientific_name' => 'Caranx crumenophthalmus', 'tausug_name' => "Tulay li'gat", 'category' => 'Scad & Trevally'],
            ['name' => 'Salay-salay', 'english_name' => 'Crevalle / Scad', 'scientific_name' => 'Carangidae', 'tausug_name' => 'Salay-salay', 'category' => 'Scad & Trevally'],
            ['name' => 'Tulingan', 'english_name' => 'Tuna / Bonito', 'scientific_name' => 'Tuna group', 'tausug_name' => "Puyan / Mangku'", 'category' => 'Tuna & Mackerel'],
            ['name' => 'Tambakol', 'english_name' => 'Yellowfin Tuna', 'scientific_name' => 'Thunnus albacares', 'tausug_name' => 'Panit gampal', 'category' => 'Tuna & Mackerel'],
            ['name' => 'Tuna', 'english_name' => 'Tuna', 'scientific_name' => 'Thunnus spp.', 'tausug_name' => 'Panit', 'category' => 'Tuna & Mackerel'],
            ['name' => 'Tangi / Tangigue', 'english_name' => 'Spanish Mackerel', 'scientific_name' => 'Scomberomorus spp.', 'tausug_name' => "Tangi'", 'category' => 'Tuna & Mackerel'],
            ['name' => 'Bonito', 'english_name' => 'Bonito', 'scientific_name' => 'Euthynnus spp.', 'tausug_name' => 'Subad', 'category' => 'Tuna & Mackerel'],
            ['name' => 'Skipjack', 'english_name' => 'Skipjack Tuna', 'scientific_name' => 'Katsuwonus pelamis', 'tausug_name' => 'Puyan', 'category' => 'Tuna & Mackerel'],
            ['name' => 'Bisugo', 'english_name' => 'Threadfin Bream', 'scientific_name' => 'Nemipterus spp.', 'tausug_name' => null, 'category' => 'Small Bottom Fish'],
            ['name' => 'Sapsap', 'english_name' => 'Slipmouth', 'scientific_name' => 'Leiognathidae', 'tausug_name' => 'Sapsap', 'category' => 'Small Bottom Fish'],
            ['name' => 'Dilis', 'english_name' => 'Anchovy', 'scientific_name' => 'Engraulidae', 'tausug_name' => 'Piyatay', 'category' => 'Sardine & Anchovy'],
            ['name' => 'Anchovy', 'english_name' => 'Anchovy', 'scientific_name' => 'Stolephorus spp.', 'tausug_name' => 'Piyatay anak bat', 'category' => 'Sardine & Anchovy'],
            ['name' => 'Garfish', 'english_name' => 'Garfish', 'scientific_name' => null, 'tausug_name' => 'Silu', 'category' => 'Flying Fish & Halfbeak'],
            ['name' => 'Whiting / Asohos', 'english_name' => 'Whiting', 'scientific_name' => 'Sillago spp.', 'tausug_name' => 'Tangbud', 'category' => 'Small Bottom Fish'],
            ['name' => 'Lapu-lapu', 'english_name' => 'Grouper', 'scientific_name' => 'Serranidae', 'tausug_name' => 'Kulapu', 'category' => 'Grouper & Sea Bass'],
            ['name' => 'Maya-maya', 'english_name' => 'Snapper', 'scientific_name' => 'Lutjanus spp.', 'tausug_name' => 'Maya-maya', 'category' => 'Snapper'],
            ['name' => 'Red Snapper', 'english_name' => 'Red Snapper', 'scientific_name' => 'Lutjanus spp.', 'tausug_name' => 'Dapak', 'category' => 'Snapper'],
            ['name' => "Russell's Snapper", 'english_name' => "Russell's Snapper", 'scientific_name' => 'Lutjanus russellii', 'tausug_name' => "Bahaba'", 'category' => 'Snapper'],
            ['name' => 'Brown-striped Snapper', 'english_name' => 'Brown-striped Snapper', 'scientific_name' => 'Lutjanus vitta', 'tausug_name' => "Bahu'-bahu'", 'category' => 'Snapper'],
            ['name' => 'Dalag', 'english_name' => 'Mudfish / Murrel', 'scientific_name' => 'Channa striata', 'tausug_name' => 'Dalag', 'category' => 'Freshwater & Brackish Fish'],
            ['name' => 'Tilapia', 'english_name' => 'Tilapia', 'scientific_name' => 'Oreochromis spp.', 'tausug_name' => 'Tilapiya', 'category' => 'Freshwater & Brackish Fish'],
            ['name' => 'Talakitok', 'english_name' => 'Trevally', 'scientific_name' => 'Carangidae', 'tausug_name' => null, 'category' => 'Scad & Trevally'],
            ['name' => 'Pagi', 'english_name' => 'Ray', 'scientific_name' => null, 'tausug_name' => 'Pagi', 'category' => 'Ray'],
            ['name' => 'Stingray', 'english_name' => 'Stingray', 'scientific_name' => 'Dasyatidae', 'tausug_name' => 'Pagi tamimik', 'category' => 'Ray'],
            ['name' => 'Eagle Ray', 'english_name' => 'Eagle Ray', 'scientific_name' => 'Aetobatus spp.', 'tausug_name' => 'Pagi batu', 'category' => 'Ray'],
            ['name' => 'Swordfish', 'english_name' => 'Swordfish', 'scientific_name' => 'Xiphias gladius', 'tausug_name' => 'Manumbuk', 'category' => 'Billfish'],
            ['name' => 'Sailfish', 'english_name' => 'Sailfish', 'scientific_name' => 'Istiophorus spp.', 'tausug_name' => 'Kandayan', 'category' => 'Billfish'],
            ['name' => 'Barracuda', 'english_name' => 'Barracuda', 'scientific_name' => 'Sphyraena spp.', 'tausug_name' => 'Bangasan / Pangaluwan', 'category' => 'Barracuda & Lizardfish'],
            ['name' => 'Lizardfish', 'english_name' => 'Lizardfish', 'scientific_name' => 'Saurida spp.', 'tausug_name' => 'Kamang', 'category' => 'Barracuda & Lizardfish'],
            ['name' => 'Goatfish', 'english_name' => 'Goatfish', 'scientific_name' => 'Upeneus spp.', 'tausug_name' => 'Timbungan', 'category' => 'Reef Fish'],
            ['name' => 'Caesio', 'english_name' => 'Caesio', 'scientific_name' => 'Caesio spp.', 'tausug_name' => 'Sulig', 'category' => 'Reef Fish'],
            ['name' => 'Denticulated Caesio', 'english_name' => 'Denticulated Caesio', 'scientific_name' => 'Caesio cuning', 'tausug_name' => 'Sulig mapan', 'category' => 'Reef Fish'],
            ['name' => 'Parrotfish', 'english_name' => 'Parrotfish', 'scientific_name' => 'Scarus spp.', 'tausug_name' => 'Ugus', 'category' => 'Reef Fish'],
            ['name' => 'Surgeonfish', 'english_name' => 'Surgeonfish', 'scientific_name' => 'Acanthurus spp.', 'tausug_name' => 'Indangan', 'category' => 'Reef Fish'],
            ['name' => 'Rabbitfish', 'english_name' => 'Rabbitfish', 'scientific_name' => 'Siganidae', 'tausug_name' => 'Bawis / Bung', 'category' => 'Reef Fish'],
            ['name' => 'Wrasse', 'english_name' => 'Wrasse', 'scientific_name' => 'Labridae', 'tausug_name' => 'Bukan / Lampit', 'category' => 'Reef Fish'],
            ['name' => 'Grouper', 'english_name' => 'Grouper', 'scientific_name' => 'Serranidae', 'tausug_name' => 'Kulapu', 'category' => 'Grouper & Sea Bass'],
            ['name' => 'Porgy', 'english_name' => 'Porgy / Sea Bream', 'scientific_name' => null, 'tausug_name' => 'Kutambak', 'category' => 'Reef Fish'],
            ['name' => 'Big-eyed Porgy', 'english_name' => 'Big-eyed Porgy', 'scientific_name' => 'Monotaxis grandoculis', 'tausug_name' => 'Kamdaman', 'category' => 'Reef Fish'],
            ['name' => 'Grunt', 'english_name' => 'Grunt', 'scientific_name' => 'Theraponidae', 'tausug_name' => 'Bigaung', 'category' => 'Reef Fish'],
            ['name' => 'Archerfish', 'english_name' => 'Archerfish', 'scientific_name' => 'Toxotes spp.', 'tausug_name' => 'Kitang / Sumpit-sumpit', 'category' => 'Reef Fish'],
            ['name' => 'Triggerfish', 'english_name' => 'Triggerfish', 'scientific_name' => 'Balistidae', 'tausug_name' => 'Pugut', 'category' => 'Reef Fish'],
            ['name' => 'Lionfish', 'english_name' => 'Lionfish', 'scientific_name' => 'Pterois spp.', 'tausug_name' => 'Tamalingking', 'category' => 'Reef Fish'],
            ['name' => 'Scorpionfish', 'english_name' => 'Scorpionfish', 'scientific_name' => 'Scorpaenidae', 'tausug_name' => "Kappu' / Lupu'", 'category' => 'Reef Fish'],
            ['name' => 'Soldierfish', 'english_name' => 'Soldierfish', 'scientific_name' => 'Holocentridae', 'tausug_name' => 'Tihik-tihik', 'category' => 'Reef Fish'],
            ['name' => 'Cowfish', 'english_name' => 'Cowfish', 'scientific_name' => 'Ostraciidae', 'tausug_name' => 'Taballung', 'category' => 'Reef Fish'],
            ['name' => 'Flying Fish', 'english_name' => 'Flying fish', 'scientific_name' => 'Exocoetidae', 'tausug_name' => 'Bingki', 'category' => 'Flying Fish & Halfbeak'],
            ['name' => 'Halfbeak', 'english_name' => 'Halfbeak', 'scientific_name' => 'Hemiramphus spp.', 'tausug_name' => 'Jungjung / Ubud-ubud', 'category' => 'Flying Fish & Halfbeak'],
            ['name' => 'Catfish', 'english_name' => 'Catfish', 'scientific_name' => null, 'tausug_name' => 'Tawti', 'category' => 'Catfish & Eel'],
            ['name' => 'Green Sea Catfish', 'english_name' => 'Green Sea Catfish', 'scientific_name' => 'Arius thalassinus', 'tausug_name' => 'Tawti sumbiling', 'category' => 'Catfish & Eel'],
            ['name' => 'Eel', 'english_name' => 'Saltwater Eel', 'scientific_name' => null, 'tausug_name' => 'Indung', 'category' => 'Catfish & Eel'],
            ['name' => 'Mudfish', 'english_name' => 'Mudfish', 'scientific_name' => 'Channa spp.', 'tausug_name' => 'Aluwan / Dalag', 'category' => 'Freshwater & Brackish Fish'],
            ['name' => 'Remora', 'english_name' => 'Remora', 'scientific_name' => 'Echeneis naucrates', 'tausug_name' => 'Kummi', 'category' => 'Other Sea Creatures'],
            ['name' => 'Sea Bass', 'english_name' => 'Barramundi', 'scientific_name' => 'Lates calcarifer', 'tausug_name' => null, 'category' => 'Grouper & Sea Bass'],
            ['name' => 'Squid', 'english_name' => 'Squid', 'scientific_name' => null, 'tausug_name' => 'Kanuus / Tābula', 'category' => 'Cephalopods'],
            ['name' => 'Cuttlefish', 'english_name' => 'Cuttlefish', 'scientific_name' => null, 'tausug_name' => 'Kulabutan', 'category' => 'Cephalopods'],
            ['name' => 'Octopus', 'english_name' => 'Octopus', 'scientific_name' => null, 'tausug_name' => 'Kugita / Kindat', 'category' => 'Cephalopods'],
            ['name' => 'Shrimp', 'english_name' => 'Shrimp / Prawn', 'scientific_name' => null, 'tausug_name' => 'Ullang / Pisayan', 'category' => 'Crustaceans'],
        ];

        $categoryIds = collect($fish)
            ->pluck('category')
            ->unique()
            ->mapWithKeys(function ($category) {
                $dropdown = ListDropdown::updateOrCreate(
                    ['name' => $category, 'classification' => 'Fish', 'type' => 'Category'],
                    ['color' => 'n/a', 'others' => 'n/a', 'is_active' => 1]
                );

                return [$category => $dropdown->id];
            });

        foreach ($fish as $row) {
            FishSpecies::updateOrCreate(
                ['name' => $row['name']],
                [
                    'local_name' => $row['name'],
                    'english_name' => $row['english_name'],
                    'scientific_name' => $row['scientific_name'],
                    'tausug_name' => $row['tausug_name'],
                    'category_id' => $categoryIds[$row['category']],
                    'unit' => 'kg',
                    'is_active' => 1,
                ]
            );
        }
    }
}
