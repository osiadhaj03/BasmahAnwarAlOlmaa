<?php

namespace App\Console\Commands;

use App\Models\QrToken;
use Illuminate\Console\Command;

class CleanExpiredQrTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qr:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired QR tokens from database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء تنظيف QR Tokens المنتهية الصلاحية...');
        
        $expiredTokens = QrToken::where('expires_at', '<', now())->count();
        $usedTokens = QrToken::whereNotNull('used_at')->count();
        
        // حذف QR Tokens المنتهية الصلاحية
        $deletedExpired = QrToken::where('expires_at', '<', now())->delete();
        
        // حذف QR Tokens المستخدمة التي مر عليها أكثر من يوم
        $deletedUsed = QrToken::whereNotNull('used_at')
            ->where('used_at', '<', now()->subDay())
            ->delete();
        
        $total = $deletedExpired + $deletedUsed;
        
        $this->info("تم حذف {$deletedExpired} token منتهي الصلاحية");
        $this->info("تم حذف {$deletedUsed} token مستخدم قديم");        $this->info("إجمالي العناصر المحذوفة: {$total}");
        
        $remaining = QrToken::count();
        $this->info("العناصر المتبقية: {$remaining}");
        
        $this->info('تم تنظيف قاعدة البيانات بنجاح!');
        
        return 0;
    }
}
