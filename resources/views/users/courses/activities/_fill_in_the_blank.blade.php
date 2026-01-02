<div class="fill-in-the-blank-activity bg-[#0f172a] p-8 pb-32 rounded-xl border-2 border-indigo-900/50 shadow-2xl relative overflow-hidden group min-h-[400px]">
    
    <!-- Background Texture -->
    <div class="absolute inset-0 opacity-5 pointer-events-none" 
         style="background-image: linear-gradient(0deg, transparent 24%, rgba(99, 102, 241, .3) 25%, rgba(99, 102, 241, .3) 26%, transparent 27%, transparent 74%, rgba(99, 102, 241, .3) 75%, rgba(99, 102, 241, .3) 76%, transparent 77%, transparent), linear-gradient(90deg, transparent 24%, rgba(99, 102, 241, .3) 25%, rgba(99, 102, 241, .3) 26%, transparent 27%, transparent 74%, rgba(99, 102, 241, .3) 75%, rgba(99, 102, 241, .3) 76%, transparent 77%, transparent); background-size: 30px 30px;">
    </div>

    <!-- Header / Prompt -->
    <div class="flex items-center gap-3 mb-8 border-b border-indigo-800/50 pb-4">
        <div class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></div>
        <div class="w-3 h-3 rounded-full bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.6)]"></div>
        <div class="w-3 h-3 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
        <span class="ml-auto font-mono text-xs text-indigo-400 tracking-widest uppercase">Input_Required</span>
    </div>

    <!-- The Question Display -->
    <div class="mb-10 relative z-10">
        <p class="text-lg md:text-xl font-mono text-cyan-300 leading-relaxed font-bold drop-shadow-md">
            {{-- Replace underscores with a visual input slot --}}
            {!! str_replace('____', '<span class="inline-block w-20 border-b-4 border-dashed border-indigo-500 mx-2 animate-pulse align-middle"></span>', $data->question ?? 'Missing question data') !!}
        </p>
    </div>
    
    <!-- The Input Form (Regular POST, No AJAX) -->
    <form action="{{ route('lessons.complete') }}" method="POST" class="relative z-10">
        @csrf
        <input type="hidden" name="lesson_id" value="{{ $currentLesson->id }}">
        
        <div class="flex flex-col md:flex-row gap-4 items-stretch">
            <div class="relative flex-grow group-focus-within:ring-2 ring-indigo-500 rounded-lg">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-indigo-500 font-mono text-xl">></span>
                </div>
                <input type="text" 
                       name="answer" 
                       class="block w-full pl-10 pr-4 py-4 bg-[#1e293b] border-2 border-slate-700 rounded-lg text-white font-mono text-lg placeholder-slate-600 focus:outline-none focus:border-cyan-400 focus:bg-[#253045] transition-all shadow-inner"
                       placeholder="Type answer here..."
                       autocomplete="off"
                       required>
            </div>
            
            <button type="submit" class="flex-shrink-0 inline-flex items-center justify-center px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-wider rounded-lg transition-all transform hover:scale-[1.02] shadow-[0_0_20px_rgba(79,70,229,0.4)] border-b-4 border-indigo-800 active:border-b-0 active:translate-y-1">
                <span>EXECUTE</span>
                <svg class="w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </button>
        </div>
    </form>

    <!-- Success Popup (Green, For Correct Answers) -->
@if(session('fill_in_blank_success'))
    <div id="success-popup" class="absolute bottom-4 left-4 right-4 bg-green-900/90 border border-green-500 text-white p-4 rounded-lg shadow-2xl flex items-center justify-between backdrop-blur-sm animate-bounce-in z-50">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <h4 class="font-bold text-green-200 text-xs uppercase tracking-wider">System Success</h4>
                <p class="font-mono text-sm">Correct answer! Lesson completed.</p>
            </div>
        </div>
        <a href="{{ session('next_redirect_url') }}" class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded font-bold transition-colors">
            Continue
        </a>
    </div>
@endif

<!-- Error Popup (Red, For Wrong Answers) -->
@if(session('error'))
    <div id="error-popup" class="absolute bottom-4 left-4 right-4 bg-red-900/90 border border-red-500 text-white p-4 rounded-lg shadow-2xl flex items-center justify-between backdrop-blur-sm animate-bounce-in z-50">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <h4 class="font-bold text-red-200 text-xs uppercase tracking-wider">System Alert</h4>
                <p class="font-mono text-sm">{{ session('error') }}</p>
            </div>
        </div>
        <button onclick="closeErrorPopup()" class="text-red-300 hover:text-white transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    <script>
        function closeErrorPopup() {
            const popup = document.getElementById('error-popup');
            if (popup) {
                popup.style.transition = 'all 0.3s ease-in';
                popup.style.opacity = '0';
                popup.style.transform = 'translateY(100%)';
                setTimeout(() => popup.classList.add('hidden'), 300);
            }
        }
    </script>
@endif

<style>
    @keyframes bounceIn {
        0% { transform: translateY(100%); opacity: 0; }
        60% { transform: translateY(-10px); opacity: 1; }
        100% { transform: translateY(0); }
    }
    .animate-bounce-in { animation: bounceIn 0.5s ease-out forwards; }
</style>