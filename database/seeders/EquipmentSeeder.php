<?php

namespace Database\Seeders;

use App\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Laptops (category_id: 1)
            ['name' => 'Dell Latitude 5540', 'category_id' => 1, 'serial_number' => 'IH-LT-0001',
             'description' => '15.6" business laptop with Intel Core i7-1355U, 16GB RAM, 512GB SSD.',
             'specifications' => ['cpu' => 'Intel i7-1355U', 'ram' => '16GB', 'storage' => '512GB SSD', 'display' => '15.6" FHD'],
             'purchase_date' => '2025-02-14', 'warranty_until' => '2028-02-14', 'condition' => 'New'],

            ['name' => 'HP EliteBook 840 G10', 'category_id' => 1, 'serial_number' => 'IH-LT-0002',
             'description' => '14" ultrabook, Intel i5-1345U, 16GB RAM, Windows 11 Pro.',
             'specifications' => ['cpu' => 'Intel i5-1345U', 'ram' => '16GB', 'storage' => '512GB SSD', 'display' => '14" WUXGA'],
             'purchase_date' => '2024-11-08', 'warranty_until' => '2027-11-08', 'condition' => 'Good'],

            ['name' => 'Lenovo ThinkPad X1 Carbon Gen 11', 'category_id' => 1, 'serial_number' => 'IH-LT-0003',
             'description' => 'Premium 14" business ultrabook, lightweight carbon-fiber chassis.',
             'specifications' => ['cpu' => 'Intel i7-1365U', 'ram' => '32GB', 'storage' => '1TB SSD', 'weight' => '1.12 kg'],
             'purchase_date' => '2025-06-01', 'warranty_until' => '2028-06-01', 'condition' => 'New'],

            ['name' => 'Apple MacBook Pro 14" M3', 'category_id' => 1, 'serial_number' => 'IH-LT-0004',
             'description' => 'Apple Silicon laptop with M3 chip, Liquid Retina XDR display.',
             'specifications' => ['cpu' => 'Apple M3', 'ram' => '16GB', 'storage' => '512GB SSD', 'display' => '14.2" Liquid Retina XDR'],
             'purchase_date' => '2025-03-20', 'warranty_until' => '2026-03-20', 'condition' => 'New'],

            ['name' => 'Asus ZenBook 14 OLED', 'category_id' => 1, 'serial_number' => 'IH-LT-0005',
             'description' => '14" OLED thin-and-light, Intel Core Ultra 7.',
             'specifications' => ['cpu' => 'Intel Core Ultra 7', 'ram' => '16GB', 'storage' => '1TB SSD', 'display' => '14" 2.8K OLED'],
             'purchase_date' => '2024-09-12', 'warranty_until' => '2026-09-12', 'condition' => 'Good'],

            ['name' => 'Acer Swift 3', 'category_id' => 1, 'serial_number' => 'IH-LT-0006',
             'description' => 'Budget 14" productivity laptop.',
             'specifications' => ['cpu' => 'AMD Ryzen 5 7530U', 'ram' => '8GB', 'storage' => '512GB SSD'],
             'purchase_date' => '2024-05-22', 'warranty_until' => '2026-05-22', 'condition' => 'Fair'],

            // Monitors (category_id: 2)
            ['name' => 'Samsung Odyssey G7 27"', 'category_id' => 2, 'serial_number' => 'IH-MN-0001',
             'description' => '27" QHD curved gaming monitor, 240Hz.',
             'specifications' => ['size' => '27"', 'resolution' => '2560x1440', 'refresh_rate' => '240Hz', 'panel' => 'VA'],
             'purchase_date' => '2025-01-10', 'warranty_until' => '2028-01-10', 'condition' => 'New'],

            ['name' => 'LG UltraFine 27" 4K', 'category_id' => 2, 'serial_number' => 'IH-MN-0002',
             'description' => '27" 4K UHD IPS display with USB-C.',
             'specifications' => ['size' => '27"', 'resolution' => '3840x2160', 'panel' => 'IPS', 'ports' => 'USB-C, HDMI, DP'],
             'purchase_date' => '2024-12-05', 'warranty_until' => '2027-12-05', 'condition' => 'Good'],

            ['name' => 'Dell UltraSharp U2723QE', 'category_id' => 2, 'serial_number' => 'IH-MN-0003',
             'description' => '27" 4K USB-C hub monitor with IPS Black panel.',
             'specifications' => ['size' => '27"', 'resolution' => '3840x2160', 'panel' => 'IPS Black', 'usb_c_pd' => '90W'],
             'purchase_date' => '2025-02-28', 'warranty_until' => '2028-02-28', 'condition' => 'New'],

            ['name' => 'HP E24 G5 FHD', 'category_id' => 2, 'serial_number' => 'IH-MN-0004',
             'description' => '23.8" Full HD IPS office monitor.',
             'specifications' => ['size' => '23.8"', 'resolution' => '1920x1080', 'panel' => 'IPS'],
             'purchase_date' => '2024-07-18', 'warranty_until' => '2027-07-18', 'condition' => 'Good'],

            // Keyboards (category_id: 3)
            ['name' => 'Logitech MX Keys S', 'category_id' => 3, 'serial_number' => 'IH-KB-0001',
             'description' => 'Wireless backlit productivity keyboard, low-profile.',
             'specifications' => ['layout' => 'Full-size', 'connection' => 'Bluetooth/USB receiver', 'backlit' => true],
             'purchase_date' => '2025-01-22', 'warranty_until' => '2027-01-22', 'condition' => 'New'],

            ['name' => 'Keychron K8 Pro', 'category_id' => 3, 'serial_number' => 'IH-KB-0002',
             'description' => 'Wireless mechanical keyboard, TKL, hot-swappable.',
             'specifications' => ['switch' => 'Gateron Brown', 'layout' => 'TKL', 'connection' => 'Bluetooth/USB-C'],
             'purchase_date' => '2024-10-03', 'warranty_until' => '2026-10-03', 'condition' => 'Good'],

            ['name' => 'Dell KB216 Wired', 'category_id' => 3, 'serial_number' => 'IH-KB-0003',
             'description' => 'Entry-level wired USB keyboard.',
             'specifications' => ['layout' => 'Full-size', 'connection' => 'USB'],
             'purchase_date' => '2024-03-11', 'warranty_until' => '2025-03-11', 'condition' => 'Fair'],

            // Mice (category_id: 4)
            ['name' => 'Logitech MX Master 3S', 'category_id' => 4, 'serial_number' => 'IH-MS-0001',
             'description' => 'Advanced wireless mouse, 8K DPI, silent clicks.',
             'specifications' => ['dpi' => '8000', 'connection' => 'Bluetooth/USB receiver', 'battery' => 'Rechargeable'],
             'purchase_date' => '2025-01-22', 'warranty_until' => '2027-01-22', 'condition' => 'New'],

            ['name' => 'Microsoft Surface Precision Mouse', 'category_id' => 4, 'serial_number' => 'IH-MS-0002',
             'description' => 'Ergonomic productivity mouse with 3 programmable buttons.',
             'specifications' => ['dpi' => '3200', 'connection' => 'Bluetooth/USB'],
             'purchase_date' => '2024-08-14', 'warranty_until' => '2026-08-14', 'condition' => 'Good'],

            ['name' => 'HP 125 Wired Mouse', 'category_id' => 4, 'serial_number' => 'IH-MS-0003',
             'description' => 'Basic USB wired optical mouse.',
             'specifications' => ['dpi' => '1200', 'connection' => 'USB'],
             'purchase_date' => '2024-02-20', 'warranty_until' => '2025-02-20', 'condition' => 'Fair'],

            // Printers (category_id: 5)
            ['name' => 'HP LaserJet Pro M404dn', 'category_id' => 5, 'serial_number' => 'IH-PR-0001',
             'description' => 'Monochrome laser printer with duplex and Ethernet.',
             'specifications' => ['type' => 'Laser Mono', 'ppm' => '40', 'duplex' => true, 'network' => 'Ethernet'],
             'purchase_date' => '2024-11-25', 'warranty_until' => '2026-11-25', 'condition' => 'Good'],

            ['name' => 'Canon PIXMA G3020', 'category_id' => 5, 'serial_number' => 'IH-PR-0002',
             'description' => 'Ink-tank color all-in-one printer with Wi-Fi.',
             'specifications' => ['type' => 'Inkjet Color', 'functions' => 'Print/Scan/Copy', 'wifi' => true],
             'purchase_date' => '2025-03-08', 'warranty_until' => '2027-03-08', 'condition' => 'New'],

            ['name' => 'Epson EcoTank L3250', 'category_id' => 5, 'serial_number' => 'IH-PR-0003',
             'description' => 'Wi-Fi all-in-one ink-tank printer.',
             'specifications' => ['type' => 'Inkjet Color', 'functions' => 'Print/Scan/Copy', 'wifi' => true],
             'purchase_date' => '2024-06-04', 'warranty_until' => '2026-06-04', 'condition' => 'Good'],

            // Chairs (category_id: 6)
            ['name' => 'Herman Miller Aeron Size B', 'category_id' => 6, 'serial_number' => 'IH-CH-0001',
             'description' => 'Ergonomic office chair with PostureFit SL.',
             'specifications' => ['size' => 'B (Medium)', 'material' => '8Z Pellicle mesh'],
             'purchase_date' => '2025-02-01', 'warranty_until' => '2037-02-01', 'condition' => 'New'],

            ['name' => 'Steelcase Leap V2', 'category_id' => 6, 'serial_number' => 'IH-CH-0002',
             'description' => 'Ergonomic task chair with LiveBack technology.',
             'specifications' => ['armrests' => '4D', 'lumbar' => 'Adjustable'],
             'purchase_date' => '2024-09-15', 'warranty_until' => '2036-09-15', 'condition' => 'Good'],

            ['name' => 'IKEA Markus', 'category_id' => 6, 'serial_number' => 'IH-CH-0003',
             'description' => 'High-back mesh office chair.',
             'specifications' => ['material' => 'Mesh + fabric', 'adjustable' => 'Height/tilt'],
             'purchase_date' => '2024-04-10', 'warranty_until' => '2034-04-10', 'condition' => 'Good'],

            // Desks (category_id: 7)
            ['name' => 'IKEA Bekant 160x80', 'category_id' => 7, 'serial_number' => 'IH-DK-0001',
             'description' => 'Standard office desk, 160x80cm, white.',
             'specifications' => ['dimensions' => '160x80 cm', 'color' => 'White'],
             'purchase_date' => '2024-04-10', 'warranty_until' => '2034-04-10', 'condition' => 'Good'],

            ['name' => 'Uplift V2 Standing Desk', 'category_id' => 7, 'serial_number' => 'IH-DK-0002',
             'description' => 'Electric height-adjustable sit/stand desk.',
             'specifications' => ['dimensions' => '72x30 in', 'motor' => 'Dual', 'range' => '25.3-50.9 in'],
             'purchase_date' => '2025-01-30', 'warranty_until' => '2032-01-30', 'condition' => 'New'],

            ['name' => 'Featherlite Executive Desk', 'category_id' => 7, 'serial_number' => 'IH-DK-0003',
             'description' => 'Wooden executive desk with drawer pedestal.',
             'specifications' => ['dimensions' => '180x90 cm', 'material' => 'Engineered wood'],
             'purchase_date' => '2023-12-18', 'warranty_until' => '2028-12-18', 'condition' => 'Fair'],

            // Others (category_id: 9)
            ['name' => 'Logitech C920 HD Pro Webcam', 'category_id' => 9, 'serial_number' => 'IH-OT-0001',
             'description' => '1080p webcam with stereo microphones.',
             'specifications' => ['resolution' => '1920x1080', 'fps' => '30', 'connection' => 'USB'],
             'purchase_date' => '2024-10-12', 'warranty_until' => '2026-10-12', 'condition' => 'Good'],

            ['name' => 'Jabra Evolve2 65 Headset', 'category_id' => 9, 'serial_number' => 'IH-OT-0002',
             'description' => 'Wireless UC stereo headset with noise-canceling mic.',
             'specifications' => ['connection' => 'Bluetooth/USB dongle', 'battery' => '37 hours'],
             'purchase_date' => '2025-02-06', 'warranty_until' => '2027-02-06', 'condition' => 'New'],

            ['name' => 'APC Back-UPS 1100VA', 'category_id' => 9, 'serial_number' => 'IH-OT-0003',
             'description' => 'Battery backup and surge protector for workstations.',
             'specifications' => ['capacity' => '1100VA/660W', 'outlets' => '6 surge + 4 battery'],
             'purchase_date' => '2024-07-28', 'warranty_until' => '2027-07-28', 'condition' => 'Good'],

            ['name' => 'TP-Link Archer AX55 Router', 'category_id' => 9, 'serial_number' => 'IH-OT-0004',
             'description' => 'Wi-Fi 6 dual-band router, AX3000.',
             'specifications' => ['standard' => 'Wi-Fi 6', 'speed' => 'AX3000', 'ports' => '4x Gigabit LAN'],
             'purchase_date' => '2024-08-30', 'warranty_until' => '2026-08-30', 'condition' => 'Good'],

            ['name' => 'Apple iPad 10th Gen', 'category_id' => 9, 'serial_number' => 'IH-OT-0005',
             'description' => '10.9" iPad with A14 Bionic chip, Wi-Fi only.',
             'specifications' => ['display' => '10.9" Liquid Retina', 'chip' => 'A14 Bionic', 'storage' => '64GB'],
             'purchase_date' => '2025-03-12', 'warranty_until' => '2026-03-12', 'condition' => 'New'],
        ];

        foreach ($items as $item) {
            $item['specifications'] = json_encode($item['specifications']);
            $item['status'] = 'Available';
            $item['assigned_to'] = null;
            Equipment::create($item);
        }
    }
}
