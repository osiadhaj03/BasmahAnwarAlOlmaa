<div class="bg-white rounded-lg shadow-lg p-6 max-w-md mx-auto">
    <div class="text-center mb-6">
        <div class="bg-blue-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
            <i class="fas fa-user-check text-2xl text-blue-600"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-2">تسجيل الحضور</h2>
        <p class="text-gray-600">{{ $lesson->subject }} - {{ $lesson->name }}</p>
        <p class="text-sm text-gray-500">{{ $lesson->teacher->name }}</p>
    </div>

    @if($hasAttendedToday)
        <!-- Already Attended -->
        <div class="text-center py-8">
            <div class="bg-green-100 rounded-full p-4 w-16 h-16 mx-auto mb-4">
                <i class="fas fa-check-circle text-2xl text-green-600"></i>
            </div>
            <h3 class="text-lg font-medium text-green-800 mb-2">تم تسجيل حضورك</h3>
            <p class="text-green-600">لقد قمت بتسجيل حضورك لهذا اليوم بنجاح</p>
        </div>
    @else
        <!-- Code Input Form -->
        <form wire:submit="submitCode" class="space-y-4">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                    اطلب الكود من معلمك وأدخله هنا
                </label>
                <input type="text" 
                       id="code"
                       wire:model="code" 
                       maxlength="6"
                       placeholder="000000"
                       class="w-full text-center text-2xl font-mono tracking-wider border-2 border-gray-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-200"
                       autocomplete="off">
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="code.length !== 6">
                <i class="fas fa-check mr-2"></i>
                تسجيل الحضور
            </button>
        </form>

        <!-- Help Section -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <h4 class="font-medium text-gray-800 mb-2">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                كيف أسجل حضوري؟
            </h4>
            <ol class="text-sm text-gray-600 space-y-1">
                <li>1. اطلب من معلمك الكود المكون من 6 أرقام</li>
                <li>2. أدخل الكود في الحقل أعلاه</li>
                <li>3. اضغط على "تسجيل الحضور"</li>
            </ol>
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-clock mr-1"></i>
                ملاحظة: الكود صالح لفترة محدودة فقط
            </p>
        </div>
    @endif

    <!-- Message Display -->
    @if($message)
        <div class="mt-4 p-4 rounded-lg 
                    {{ $messageType === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : '' }}
                    {{ $messageType === 'error' ? 'bg-red-100 text-red-800 border border-red-200' : '' }}
                    {{ $messageType === 'warning' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : '' }}">
            <div class="flex items-center">
                @if($messageType === 'success')
                    <i class="fas fa-check-circle mr-2"></i>
                @elseif($messageType === 'error')
                    <i class="fas fa-exclamation-circle mr-2"></i>
                @elseif($messageType === 'warning')
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                @endif
                {{ $message }}
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('clear-message', () => {
            setTimeout(() => {
                Livewire.dispatch('clearMessage');
            }, 5000);
        });
    });

    // Auto-format code input
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        if (codeInput) {
            codeInput.addEventListener('input', function(e) {
                // Only allow numbers
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    });
</script>
@endpush
