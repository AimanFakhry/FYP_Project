<div class="sandbox-activity flex flex-col h-full bg-[#0f172a] rounded-xl border border-slate-700 shadow-2xl overflow-hidden">
    
    <!-- Toolbar -->
    <div class="bg-[#1e293b] border-b border-slate-700 px-4 py-3 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
            <span class="text-slate-300 font-bold text-sm tracking-wide">CODE EDITOR</span>
        </div>
        
        <!-- Hidden Language Input -->
        @php
            $lang = 'php'; 
            if (stripos($course->name, 'c++') !== false) $lang = 'cpp';
            if (stripos($course->name, 'javascript') !== false) $lang = 'js';
        @endphp
        <input type="hidden" id="sandbox-lang" value="{{ $lang }}">

        <div class="flex items-center gap-3">
             <span id="run-status" class="text-xs font-mono text-cyan-400 animate-pulse hidden">PROCESSING...</span>
             <button id="run-code-btn" class="flex items-center gap-2 bg-green-600 hover:bg-green-500 text-white px-4 py-1.5 rounded text-xs font-bold uppercase tracking-wider transition-all shadow-lg hover:shadow-green-900/50 border border-green-500">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                Run Code
            </button>
        </div>
    </div>

    <!-- Main Workspace Grid -->
    <div class="flex flex-col md:flex-row h-[600px] divide-y md:divide-y-0 md:divide-x divide-slate-700">
        
        <!-- Editor Column -->
        <div class="w-full md:w-2/3 bg-[#1e1e1e] relative group">
            <div class="absolute top-0 left-0 w-8 h-full bg-[#252526] border-r border-[#333] flex flex-col items-center pt-2 text-slate-600 text-xs font-mono select-none">
                <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span>
            </div>
            <textarea id="code-editor" 
                      class="w-full h-full bg-transparent text-gray-200 font-mono text-base p-4 pl-10 border-none outline-none resize-none leading-relaxed" 
                      spellcheck="false"
                      placeholder="// Write your code here...">{{ $data->starting_code ?? '' }}</textarea>
        </div>

        <!-- Output Column -->
        <div class="w-full md:w-1/3 bg-[#000000] flex flex-col">
            <div class="bg-[#1e293b] px-3 py-2 border-b border-slate-700">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Console Output</span>
            </div>
            <div class="flex-grow p-4 font-mono text-sm overflow-auto">
                <pre id="output-console" class="text-green-400 whitespace-pre-wrap break-words">Ready for input...</pre>
            </div>
        </div>
    </div>

    <!-- Bottom Action Bar (With Status) -->
    <div class="bg-[#1e293b] border-t border-slate-700 p-4 flex justify-between items-center">
        <p class="text-xs text-slate-400 hidden sm:block font-mono">
            Status: <span id="docker-status" class="text-yellow-400 font-bold">Checking...</span> | Environment: <span class="text-blue-400">{{ strtoupper($lang) }}</span>
        </p>

        <form action="{{ route('lessons.complete') }}" method="POST">
            @csrf
            <input type="hidden" name="lesson_id" value="{{ $currentLesson->id }}">
            <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-2 rounded-lg transition-all shadow-lg border border-indigo-500 hover:shadow-indigo-900/50">
                Mission Complete <span class="text-lg">&rarr;</span>
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const runBtn = document.getElementById('run-code-btn');
    const codeEditor = document.getElementById('code-editor');
    const outputConsole = document.getElementById('output-console');
    const langInput = document.getElementById('sandbox-lang');
    const statusSpan = document.getElementById('run-status');
    const dockerStatus = document.getElementById('docker-status');

    // --- 1. Check Connection Function ---
    async function checkConnection() {
        const lang = langInput.value;
        
        if (lang === 'js') {
            if (dockerStatus) {
                dockerStatus.className = 'text-green-400 font-bold';
                dockerStatus.textContent = 'Connected (Local)';
            }
            return;
        }

        try {
            const response = await fetch("{{ route('lessons.check_docker') }}");
            const data = await response.json();
            
            if (dockerStatus) {
                if (data.connected) {
                    dockerStatus.className = 'text-green-400 font-bold';
                    dockerStatus.textContent = 'Connected';
                } else {
                    dockerStatus.className = 'text-red-400 font-bold';
                    dockerStatus.textContent = 'Disconnected';
                }
            }
        } catch (e) {
            if (dockerStatus) {
                dockerStatus.className = 'text-red-400 font-bold';
                dockerStatus.textContent = 'Offline';
            }
        }
    }

    // Run check immediately
    checkConnection();

    // --- 2. Tab Support ---
    codeEditor.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            this.value = this.value.substring(0, start) + "    " + this.value.substring(end);
            this.selectionStart = this.selectionEnd = start + 4;
        }
    });

    // --- 3. Run Code Logic ---
    if (runBtn) {
        runBtn.addEventListener('click', async () => {
            const code = codeEditor.value;
            const lang = langInput.value;

            // UI State: Running
            statusSpan.classList.remove('hidden');
            runBtn.disabled = true;
            runBtn.classList.add('opacity-50', 'cursor-not-allowed');
            outputConsole.textContent = '> Executing...';
            outputConsole.className = 'text-yellow-400 whitespace-pre-wrap break-words';

            // JS Handler
            if (lang === 'js') {
                try {
                    let capturedOutput = '';
                    const oldLog = console.log;
                    console.log = (...args) => { capturedOutput += args.join(' ') + '\n'; };
                    eval(code);
                    console.log = oldLog;
                    
                    outputConsole.textContent = capturedOutput || '> (Program exited with no output)';
                    outputConsole.className = 'text-green-400 whitespace-pre-wrap break-words';
                } catch (e) {
                    outputConsole.textContent = `> Error: ${e.message}`;
                    outputConsole.className = 'text-red-500 whitespace-pre-wrap break-words';
                }
                statusSpan.classList.add('hidden');
                runBtn.disabled = false;
                runBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                return;
            }

            // PHP/C++ Handler
            try {
                const response = await fetch("{{ route('lessons.run') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ code, lang })
                });

                if (!response.ok) throw new Error(`Server error: ${response.statusText}`);

                const result = await response.json();

                if (result.error) {
                    outputConsole.textContent = `> [SYSTEM ERROR]\n${result.error}`;
                    outputConsole.className = 'text-red-500 whitespace-pre-wrap break-words';
                } else {
                    outputConsole.textContent = result.output || '> (Program exited with no output)';
                    outputConsole.className = 'text-green-400 whitespace-pre-wrap break-words';
                }
            } catch (error) {
                outputConsole.textContent = `> Connection Failure: ${error.message}`;
                outputConsole.className = 'text-red-500 whitespace-pre-wrap break-words';
            } finally {
                statusSpan.classList.add('hidden');
                runBtn.disabled = false;
                runBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
});
</script>