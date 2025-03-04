<?php

namespace App\Console\Commands;

use App\Models\DynamicQRCode;
use App\Models\ScanLog;
use App\Models\StaticQRCode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedExampleData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:seed-examples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with example QR codes and scans';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = User::where('username', 'admin')->first();

        if (!$admin) {
            $this->error('Admin user not found. Please run php artisan qr:create-admin first');
            return Command::FAILURE;
        }

        $this->info('Seeding dynamic QR codes...');
        $this->seedDynamicQRCodes($admin->id);

        $this->info('Seeding static QR codes...');
        $this->seedStaticQRCodes($admin->id);

        $this->info('Seeding scan logs...');
        $this->seedScanLogs();

        $this->info('Example data has been seeded successfully!');
        return Command::SUCCESS;
    }

    private function seedDynamicQRCodes($userId)
    {
        $examples = [
            [
                'filename' => 'Company Website',
                'redirect_identifier' => Str::random(10),
                'url' => 'https://example.com',
                'foreground_color' => '#000000',
                'background_color' => '#FFFFFF',
                'precision' => 'L',
                'size' => 200,
                'scan_count' => rand(50, 200),
                'status' => true,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ],
            [
                'filename' => 'Special Promotion',
                'redirect_identifier' => Str::random(10),
                'url' => 'https://example.com/promo',
                'foreground_color' => '#4A5568',
                'background_color' => '#FFC107',
                'precision' => 'M',
                'size' => 300,
                'scan_count' => rand(20, 100),
                'status' => true,
                'created_at' => Carbon::now()->subDays(rand(1, 15)),
            ],
            [
                'filename' => 'Product Catalog',
                'redirect_identifier' => Str::random(10),
                'url' => 'https://example.com/catalog',
                'foreground_color' => '#2C5282',
                'background_color' => '#FFFFFF',
                'precision' => 'Q',
                'size' => 250,
                'scan_count' => rand(30, 150),
                'status' => true,
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
            ],
            [
                'filename' => 'Contact Form',
                'redirect_identifier' => Str::random(10),
                'url' => 'https://example.com/contact',
                'foreground_color' => '#1A202C',
                'background_color' => '#E2E8F0',
                'precision' => 'H',
                'size' => 200,
                'scan_count' => rand(10, 80),
                'status' => true,
                'created_at' => Carbon::now()->subDays(rand(1, 20)),
            ],
            [
                'filename' => 'Digital Business Card',
                'redirect_identifier' => Str::random(10),
                'url' => 'https://example.com/card',
                'foreground_color' => '#553C9A',
                'background_color' => '#FFFFFF',
                'precision' => 'M',
                'size' => 250,
                'scan_count' => rand(40, 120),
                'status' => true,
                'created_at' => Carbon::now()->subDays(rand(1, 5)),
            ],
        ];

        foreach ($examples as $example) {
            DynamicQRCode::create(array_merge($example, ['user_id' => $userId]));
            $this->output->write('.');
        }

        $this->info("\nCreated " . count($examples) . " dynamic QR codes");
    }

    private function seedStaticQRCodes($userId)
    {
        $examples = [
            [
                'filename' => 'Text Message',
                'content_type' => 'text',
                'content' => 'Welcome to our store! Show this message at checkout for a 10% discount.',
                'foreground_color' => '#000000',
                'background_color' => '#FFFFFF',
                'precision' => 'M',
                'size' => 200,
                'format' => 'png',
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ],
            [
                'filename' => 'Contact Email',
                'content_type' => 'email',
                'content' => 'mailto:info@example.com?subject=QR%20Code%20Inquiry',
                'foreground_color' => '#2B6CB0',
                'background_color' => '#FFFFFF',
                'precision' => 'L',
                'size' => 250,
                'format' => 'png',
                'created_at' => Carbon::now()->subDays(rand(1, 25)),
            ],
            [
                'filename' => 'Support Hotline',
                'content_type' => 'phone',
                'content' => 'tel:+1234567890',
                'foreground_color' => '#2F855A',
                'background_color' => '#FFFFFF',
                'precision' => 'Q',
                'size' => 200,
                'format' => 'png',
                'created_at' => Carbon::now()->subDays(rand(1, 20)),
            ],
            [
                'filename' => 'WhatsApp Contact',
                'content_type' => 'whatsapp',
                'content' => 'https://wa.me/1234567890?text=Hello%20from%20QR%20code',
                'foreground_color' => '#25D366',
                'background_color' => '#FFFFFF',
                'precision' => 'M',
                'size' => 300,
                'format' => 'png',
                'created_at' => Carbon::now()->subDays(rand(1, 15)),
            ],
            [
                'filename' => 'Office Location',
                'content_type' => 'location',
                'content' => 'geo:37.7749,-122.4194',
                'foreground_color' => '#C53030',
                'background_color' => '#FFFFFF',
                'precision' => 'H',
                'size' => 250,
                'format' => 'png',
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
            ],
            [
                'filename' => 'Office WiFi',
                'content_type' => 'wifi',
                'content' => 'WIFI:S:Office-Guest;T:WPA;P:Welcome2023;H:false;;',
                'foreground_color' => '#3182CE',
                'background_color' => '#EBF8FF',
                'precision' => 'Q',
                'size' => 300,
                'format' => 'png',
                'created_at' => Carbon::now()->subDays(rand(1, 5)),
            ],
        ];

        foreach ($examples as $example) {
            StaticQRCode::create(array_merge($example, ['user_id' => $userId]));
            $this->output->write('.');
        }

        $this->info("\nCreated " . count($examples) . " static QR codes");
    }

    private function seedScanLogs()
    {
        $dynamicQRs = DynamicQRCode::all();
        $staticQRs = StaticQRCode::all();
        $scansCreated = 0;

        // Create scan logs for dynamic QR codes
        foreach ($dynamicQRs as $qr) {
            $scanCount = $qr->scan_count;
            
            for ($i = 0; $i < $scanCount; $i++) {
                $daysAgo = rand(0, 30);
                $hoursAgo = rand(0, 23);
                $minutesAgo = rand(0, 59);
                
                ScanLog::create([
                    'qr_code_id' => $qr->id,
                    'qr_code_type' => 'dynamic',
                    'timestamp' => Carbon::now()->subDays($daysAgo)->subHours($hoursAgo)->subMinutes($minutesAgo),
                    'ip_address' => $this->getRandomIP(),
                    'user_agent' => $this->getRandomUserAgent(),
                ]);
                $scansCreated++;
                
                if ($scansCreated % 50 == 0) {
                    $this->output->write('.');
                }
            }
        }

        // Create scan logs for static QR codes (less scans for these)
        foreach ($staticQRs as $qr) {
            $scanCount = rand(5, 50);
            
            for ($i = 0; $i < $scanCount; $i++) {
                $daysAgo = rand(0, 30);
                $hoursAgo = rand(0, 23);
                $minutesAgo = rand(0, 59);
                
                ScanLog::create([
                    'qr_code_id' => $qr->id,
                    'qr_code_type' => 'static',
                    'timestamp' => Carbon::now()->subDays($daysAgo)->subHours($hoursAgo)->subMinutes($minutesAgo),
                    'ip_address' => $this->getRandomIP(),
                    'user_agent' => $this->getRandomUserAgent(),
                ]);
                $scansCreated++;
                
                if ($scansCreated % 50 == 0) {
                    $this->output->write('.');
                }
            }
        }

        $this->info("\nCreated $scansCreated scan logs");
    }

    private function getRandomIP()
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    }

    private function getRandomUserAgent()
    {
        $userAgents = [
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Android 12; Mobile; rv:68.0) Gecko/68.0 Firefox/96.0',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36',
            'Mozilla/5.0 (iPad; CPU OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1',
        ];

        return $userAgents[array_rand($userAgents)];
    }
}
