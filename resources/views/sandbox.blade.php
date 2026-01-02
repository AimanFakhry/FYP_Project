<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Language Code Sandbox</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Prism.js theme for syntax highlighting */
        @import url("https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-coy.min.css");
        
        .sandbox-container {
            height: calc(100vh - 4rem); /* Full viewport height minus a typical navbar height */
        }
        .code-input-area {
            height: 100%;
            min-height: 400px;
        }
        /* Custom styling for the output area */
        #text-output {
            background-color: #1f2937; /* Dark background matching the editor */
            color: #ffffff;
            font-family: monospace;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <!-- Include the Navbar (assuming it's available) -->
    @include('partials.navbar')

    <div class="flex flex-col sandbox-container">
        
        <!-- Controls Bar for the Sandbox -->
        <div class="flex items-center justify-between p-3 bg-gray-800 shadow-lg h-16 flex-shrink-0">
            <h1 class="text-xl font-bold text-white">Code Execution Playground</h1>
            
            <div class="flex items-center space-x-4">
                
                <label for="language-select" class="text-white font-semibold">Language:</label>
                <select id="language-select" class="p-2 rounded-lg bg-gray-700 text-white border border-gray-600 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="javascript">JavaScript (Client-side)</option>
                    <option value="cpp">C++ (Server API)</option>
                    <option value="php">PHP (Server API)</option>
                </select>

                <button id="run-code-btn" class="px-5 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors duration-200 shadow-md">
                    Run Code
                </button>
                <button id="view-code-btn" class="px-5 py-2 bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-600 transition-colors duration-200 shadow-md">
                    View Code
                </button>
            </div>
        </div>

        <!-- Main Layout: Editor (Top) and Output (Bottom) -->
        <div class="flex-1 flex flex-col">
            
            <!-- Code Editor (Takes up the top half) -->
            <textarea 
                id="code-input" 
                class="code-input-area flex-1 p-4 text-sm font-mono focus:outline-none resize-none bg-gray-900 text-gray-100" 
                placeholder="Type your code here..."
            >// Select a language to begin.
// JavaScript will run in the browser (HTML/CSS included).
// C++ and PHP require your secure API backend to execute.
            </textarea>

            <!-- Output/Preview Area (Takes up the bottom half) -->
            <div class="flex-1 flex flex-col border-t border-gray-300">
                <div class="p-2 bg-gray-600 text-sm font-semibold text-white flex-shrink-0">Output / Live Preview</div>
                
                <!-- Code Execution Output (using iframe for JS, text div for C++/PHP results) -->
                <iframe id="code-output" sandbox="allow-scripts allow-same-origin" class="flex-1 w-full h-full bg-white border-0 hidden"></iframe>
                
                <!-- Code Display Area (Hidden by default, used for syntax highlighting) -->
                <div id="code-display" class="hidden flex-1 p-4 overflow-y-auto bg-gray-100">
                    <pre class="rounded-lg shadow-inner"><code id="highlighted-code" class="language-javascript"></code></pre>
                </div>
                
                <!-- Default Text Output Area for C++/PHP results/warnings -->
                <div id="text-output" class="flex-1 p-4 overflow-y-auto">
                    <p class="text-gray-400">Select a language and press "Run Code" to see the output.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Prism.js Scripts for Syntax Highlighting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-cpp.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            const input = document.getElementById('code-input');
            const langSelect = document.getElementById('language-select');
            const outputFrame = document.getElementById('code-output');
            const textOutput = document.getElementById('text-output');
            const runBtn = document.getElementById('run-code-btn');
            const viewBtn = document.getElementById('view-code-btn');
            const codeDisplay = document.getElementById('code-display');
            const highlightedCode = document.getElementById('highlighted-code');
            
            let currentMode = 'output'; 
            let currentLanguage = langSelect.value;

            // Define snippets and classes for each language
            const languageMap = {
                'javascript': { 
                    class: 'language-markup', // Use markup for JS/HTML/CSS combination
                    usesIframe: true, 
                    snippet: '// Write your JavaScript, HTML, and CSS here:\nconsole.log("Hello JS!");\n<h1>JS Output</h1>' 
                },
                'cpp': { 
                    class: 'language-cpp', 
                    usesIframe: false, 
                    snippet: '// C++ requires server execution (Docker)\n#include <iostream>\n\nint main() {\n    std::cout << "Hello C++!\\n";\n    return 0;\n}' 
                },
                'php': { 
                    class: 'language-php', 
                    usesIframe: false, 
                    snippet: '// PHP requires server execution (Docker)\n<?php\n\n$name = "World";\necho "Hello $name!\\n";\n\n?>' 
                },
            };

            const toggleView = () => {
                if (currentMode === 'output') {
                    // Switch to Code Display mode
                    currentMode = 'code';
                    outputFrame.style.display = 'none';
                    textOutput.style.display = 'none';
                    codeDisplay.style.display = 'block';
                    viewBtn.textContent = 'Show Preview';

                    // Prepare code for highlighting
                    const codeToDisplay = input.value;
                    const escapedCode = codeToDisplay.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    
                    highlightedCode.textContent = escapedCode;
                    highlightedCode.className = languageMap[currentLanguage]?.class || 'language-javascript';
                    Prism.highlightElement(highlightedCode);

                } else {
                    // Switch back to Output mode
                    currentMode = 'output';
                    codeDisplay.style.display = 'none';
                    viewBtn.textContent = 'View Code';
                    
                    // Show the correct output element based on language
                    if (languageMap[currentLanguage]?.usesIframe) {
                        outputFrame.style.display = 'block';
                        textOutput.style.display = 'none';
                    } else {
                        outputFrame.style.display = 'none';
                        textOutput.style.display = 'block';
                    }
                }
            };
            
            const displayOutput = (htmlContent) => {
                if (languageMap[currentLanguage]?.usesIframe) {
                    const iframeDocument = outputFrame.contentDocument || outputFrame.contentWindow.document;
                    iframeDocument.open();
                    iframeDocument.write(htmlContent);
                    iframeDocument.close();
                } else {
                    textOutput.innerHTML = htmlContent;
                }
            };

            const runCode = async () => {
                const code = input.value;
                
                // Ensure output view is active
                if (currentMode === 'code') toggleView();

                if (currentLanguage === 'javascript') {
                    // ----------------------------------------------------
                    // CLIENT-SIDE EXECUTION (JavaScript/HTML/CSS)
                    // ----------------------------------------------------
                    const fullHtml = `
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <style>body { margin: 8px; font-family: sans-serif; }</style>
                        </head>
                        <body>
                            ${code}
                        </body>
                        </html>
                    `;
                    displayOutput(fullHtml);

                } else {
                    // ----------------------------------------------------
                    // SERVER-SIDE EXECUTION (C++ / PHP) VIA API
                    // ----------------------------------------------------
                    
                    runBtn.disabled = true;
                    runBtn.textContent = 'Running...';
                    runBtn.classList.remove('bg-green-500', 'hover:bg-green-600');
                    runBtn.classList.add('bg-gray-500', 'cursor-not-allowed');
                    
                    displayOutput(`
                        <div class="p-4 text-center">
                            <svg class="animate-spin h-5 w-5 mr-3 inline text-blue-400" viewBox="0 0 24 24"></svg>
                            <span class="text-blue-400 font-semibold">Executing ${currentLanguage.toUpperCase()} on Server...</span>
                            <p class="text-sm text-gray-400 mt-1">This requires a dedicated API to run the code in a secure Docker container.</p>
                        </div>
                    `);

                    try {
                        // NOTE: This fetch call is a placeholder for your secure Docker execution API
                        const response = await fetch('/api/execute-code', { 
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                code: code,
                                language: currentLanguage
                            }),
                        });

                        const result = await response.json();
                        let outputHtml = '';

                        if (response.ok && result.output) {
                            outputHtml = `
                                <div class="p-4">
                                    <h1 class="text-xl font-bold text-green-400 mb-2">Execution Success</h1>
                                    <pre class="bg-gray-800 p-3 rounded-lg text-sm text-white whitespace-pre-wrap">${result.output}</pre>
                                </div>
                            `;
                        } else {
                             const errorMessage = result.error || 'Server execution failed. Did you set up the Docker backend?';
                             outputHtml = `
                                <div class="p-4">
                                    <h1 class="text-xl font-bold text-red-400 mb-2">Execution Error</h1>
                                    <pre class="bg-gray-800 p-3 rounded-lg text-sm text-red-300 whitespace-pre-wrap">${errorMessage}</pre>
                                </div>
                            `;
                        }
                        displayOutput(outputHtml);

                    } catch (error) {
                        console.error('API execution error:', error);
                        displayOutput(`
                            <div class="p-4">
                                <h1 class="text-xl font-bold text-red-400 mb-2">Connection Error</h1>
                                <p class="text-gray-400">Could not connect to the execution server API (/api/execute-code).</p>
                            </div>
                        `);
                    } finally {
                        runBtn.disabled = false;
                        runBtn.textContent = 'Run Code';
                        runBtn.classList.remove('bg-gray-500', 'cursor-not-allowed');
                        runBtn.classList.add('bg-green-500', 'hover:bg-green-600');
                    }
                }
            };

            const loadLanguage = (lang) => {
                currentLanguage = lang;
                
                // Update text area content with the new snippet
                input.value = languageMap[lang].snippet;

                // Toggle output modes based on language type
                if (languageMap[lang]?.usesIframe) {
                    outputFrame.style.display = 'block';
                    textOutput.style.display = 'none';
                    // Run JS code immediately for a quick preview
                    runCode(); 
                } else {
                    outputFrame.style.display = 'none';
                    textOutput.style.display = 'block';
                    displayOutput(`<p class="text-gray-400">Code will be executed via the server API when you press "Run Code".</p>`);
                }

                // If currently viewing code display, switch back to output
                if (currentMode === 'code') {
                    currentMode = 'output';
                    codeDisplay.style.display = 'none';
                    viewBtn.textContent = 'View Code';
                }
            };
            

            // --- Event Listeners ---
            runBtn.addEventListener('click', runCode);
            viewBtn.addEventListener('click', toggleView);
            langSelect.addEventListener('change', (e) => loadLanguage(e.target.value));

            // Initial load for the default language (JavaScript)
            loadLanguage(langSelect.value);
        });
    </script>

</body>
</html>
