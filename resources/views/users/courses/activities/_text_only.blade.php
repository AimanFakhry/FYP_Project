<div class="text-only-activity bg-[#0f172a] p-6 md:p-8 rounded-xl border border-slate-700 shadow-2xl relative overflow-hidden">
    
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6 border-b border-indigo-900/50 pb-4">
        <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
        <span class="text-xs font-mono text-blue-400 tracking-widest uppercase">Data_Log: Reading_Material</span>
    </div>

    <!-- Removed extra content display to avoid doubling with main lesson content -->

    <div class="flex items-center justify-between mt-8 bg-slate-800/50 p-4 rounded-lg border border-slate-700">
        <div class="hidden sm:block">
            <p class="text-sm text-white font-bold">Status: <span class="text-yellow-400">Pending Acknowledgement</span></p>
            <p class="text-xs text-slate-500">Confirm reading to proceed.</p>
        </div>
        
        <form action="{{ route('lessons.complete') }}" method="POST">
            @csrf
            <input type="hidden" name="lesson_id" value="{{ $currentLesson->id }}">
            <button type="submit" class="inline-flex items-center bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-3 rounded-lg transition-all shadow-lg shadow-indigo-900/40 border border-indigo-500 hover:border-indigo-400 group">
                <span class="mr-2">MISSION COMPLETE</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </form>
    </div>
</div>