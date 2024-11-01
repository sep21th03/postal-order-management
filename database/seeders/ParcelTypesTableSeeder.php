<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ParcelType;
class ParcelTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parcelTypes = [
            [
                'name' => 'Hàng hóa thông thường',
                'description' => 'Hàng hóa không có yêu cầu đặc biệt',
                'is_fragile' => false,
                'is_high_value' => false,
                'is_perishable' => false,
                'is_dangerous' => false,
                'is_flammable' => false,
                'is_cold_storage' => false,
                'is_international' => false,
                'is_non_standard' => false
            ],
            [
                'name' => 'Hàng hóa dễ vỡ',
                'description' => 'Các vật phẩm dễ vỡ, cần đóng gói cẩn thận',
                'is_fragile' => true
            ],
            [
                'name' => 'Hàng hóa giá trị cao',
                'description' => 'Các vật phẩm có giá trị cao, cần bảo quản đặc biệt',
                'is_high_value' => true
            ],
            [
                'name' => 'Hàng hóa dễ hư hỏng',
                'description' => 'Các sản phẩm dễ hư hỏng, cần vận chuyển nhanh',
                'is_perishable' => true
            ],
            [
                'name' => 'Hàng nguy hiểm',
                'description' => 'Hàng hóa có nguy cơ gây hại, cần tuân thủ quy định nghiêm ngặt',
                'is_dangerous' => true
            ],
            [
                'name' => 'Hàng dễ cháy nổ',
                'description' => 'Các vật liệu dễ cháy, cần bảo quản đặc biệt',
                'is_flammable' => true
            ],
            [
                'name' => 'Hàng lạnh',
                'description' => 'Hàng hóa cần được bảo quản ở nhiệt độ thấp',
                'is_cold_storage' => true
            ],
            [
                'name' => 'Hàng hóa quốc tế',
                'description' => 'Hàng hóa vận chuyển qua biên giới, cần giấy tờ và thủ tục đặc biệt',
                'is_international' => true
            ],
            [
                'name' => 'Hàng hóa phi tiêu chuẩn',
                'description' => 'Các vật phẩm có kích thước hoặc trọng lượng ngoài quy chuẩn',
                'is_non_standard' => true
            ]
        ];

        foreach ($parcelTypes as $type) {
            ParcelType::create($type);
        }
    
    }
}
