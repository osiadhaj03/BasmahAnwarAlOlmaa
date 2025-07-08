<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">جلسة تسجيل الحضور</h2>
        <p class="text-gray-600">{{ $lesson->subject }} - {{ $lesson->name }}</p>
    </div>

    @if(!$isActive)
        <!-- Start Session Controls -->
        <div class="space-y-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع الجلسة</label>
                <select wire:model="sessionType" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="code">كود رقمي</option>
                    <option value="qr">رمز QR</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">مدة الصلاحية (بالدقائق)</label>
                <select wire:model="duration" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="1">دقيقة واحدة</option>
                    <option value="2">دقيقتان</option>
                    <option value="3">3 دقائق</option>
                    <option value="5">5 دقائق</option>
                </select>
            </div>
            
            <button wire:click="startSession" 
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                <i class="fas fa-play mr-2"></i>
                بدء جلسة الحضور
            </button>
        </div>
    @else
        <!-- Active Session Display -->
        <div class="text-center space-y-6">
            <!-- Session Type Badge -->
            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $sessionType === 'code' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                @if($sessionType === 'code')
                    <i class="fas fa-hashtag mr-1"></i>
                    كود رقمي
                @else
                    <i class="fas fa-qrcode mr-1"></i>
                    رمز QR
                @endif
            </div>

            @if($sessionType === 'code')
                <!-- Code Display -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-8 border-2 border-blue-200">
                    <div class="text-6xl font-mono font-bold text-blue-800 tracking-wider mb-4">
                        {{ $currentCode }}
                    </div>
                    <p class="text-blue-600 text-lg">اطلب من الطلاب إدخال هذا الكود</p>
                </div>
            @else
                <!-- QR Code Display -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-xl p-8 border-2 border-purple-200">
                    <div class="mb-4">
                        {!! QrCode::size(200)->generate($currentCode) !!}
                    </div>
                    <p class="text-purple-600 text-lg">اطلب من الطلاب مسح هذا الرمز</p>
                </div>
            @endif

            <!-- Timer -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-center space-x-2 text-gray-700">
                    <i class="fas fa-clock"></i>
                    <span class="font-medium">الوقت المتبقي:</span>
                    <span class="font-mono text-lg font-bold text-red-500" x-data="{ time: @entangle('remainingTime') }" 
                          x-init="setInterval(() => { if(time > 0) time-- }, 1000)">
                        <span x-text="Math.floor(time / 60)"></span>:<span x-text="(time % 60).toString().padStart(2, '0')"></span>
                    </span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4 justify-center">
                <button wire:click="refreshCode" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-sync-alt mr-2"></i>
                    تجديد الكود
                </button>
                
                <button wire:click="stopSession" 
                        class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-stop mr-2"></i>
                    إيقاف الجلسة
                </button>
            </div>
        </div>
    @endif

    <!-- Auto-refresh every 5 seconds -->
    <div wire:poll.5s="refreshCurrentSession"></div>
</div>

@push('scripts')
<script>
    // Listen to Livewire events
    document.addEventListener('livewire:init', () => {
        Livewire.on('session-started', (event) => {
            // Show success notification
            if (window.showNotification) {
                window.showNotification(event.message, 'success');
            }
        });

        Livewire.on('session-stopped', (event) => {
            if (window.showNotification) {
                window.showNotification(event.message, 'info');
            }
        });

        Livewire.on('code-refreshed', (event) => {
            if (window.showNotification) {
                window.showNotification(event.message, 'info');
            }
        });

        Livewire.on('session-error', (event) => {
            if (window.showNotification) {
                window.showNotification(event.message, 'error');
            }
        });
    });
</script>
@endpush
