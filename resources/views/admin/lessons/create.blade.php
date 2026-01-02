<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Add Lesson - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #F3F4F6; }
        .code-input {
            font-family: 'Menlo', 'Monaco', 'Courier New', monospace;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="antialiased text-gray-800">

    @include('partials.admin-navbar')

    <main class="pt-20 pb-12">
        <div class="container mx-auto px-4 max-w-4xl">
            
            <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">Add New Lesson</h1>
                        <p class="text-xs text-gray-500 mt-1">Group: <span class="font-medium text-gray-700">{{ $group->title }}</span></p>
                    </div>
                    <a href="{{ route('admin.courses.show', $group->course) }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.lessons.store', $group) }}" method="POST">
                        @csrf
                        
                        <!-- Top Row -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Lesson Title</label>
                                <input type="text" name="title" id="title" required placeholder="e.g. Introduction to Variables"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                            </div>
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                                <input type="number" name="order" id="order" required value="{{ $group->lessons->count() + 1 }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                            </div>
                        </div>

                        <!-- Intro Content -->
                        <div id="intro-content-section" class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Introduction Content</label>
                            <textarea name="content" id="content" rows="3" 
                                      class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border"
                                      placeholder="Brief text displayed before the activity..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Supports basic HTML.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Activity Type Selector -->
                            <div>
                                <label for="activity_type" class="block text-sm font-medium text-gray-700 mb-1">Activity Type</label>
                                <select name="activity_type" id="activity_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border bg-gray-50">
                                    <option value="text_only">Text Only (Reading)</option>
                                    <option value="fill_in_the_blank">Fill in the Blank (Quiz)</option>
                                    <option value="sandbox">Code Sandbox (Coding)</option>
                                </select>
                            </div>
                            <!-- Achievement -->
                            <div>
                                <label for="achievement_id" class="block text-sm font-medium text-gray-700 mb-1">Achievement Reward</label>
                                <select name="achievement_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                                    <option value="">-- None --</option>
                                    @foreach($achievements as $achievement)
                                        <option value="{{ $achievement->id }}">{{ $achievement->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="border-gray-200 mb-8">

                        <!-- DYNAMIC SECTIONS -->
                        
                        <!-- 1. Text Only -->
                        <div id="section-text_only" class="activity-section hidden">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Text Lesson Configuration</h3>
                            <div class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Reading Material</label>
                                <textarea name="text_only_content" rows="8" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border"></textarea>
                            </div>
                        </div>

                        <!-- 2. Fill In Blank -->
                        <div id="section-fill_in_the_blank" class="activity-section hidden">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Quiz Configuration</h3>
                            <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-yellow-800 mb-1">Question / Snippet</label>
                                    <textarea name="fib_question" rows="3" class="block w-full rounded-md border-yellow-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm py-2 px-3 border font-mono" placeholder="Use ____ for the blank."></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-yellow-800 mb-1">Correct Answer</label>
                                    <input type="text" name="fib_answer" class="block w-full rounded-md border-yellow-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500 sm:text-sm py-2 px-3 border">
                                </div>
                            </div>
                        </div>

                        <!-- 3. Sandbox -->
                        <div id="section-sandbox" class="activity-section hidden">
                            <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Sandbox Configuration</h3>
                            <div class="bg-slate-900 p-4 rounded-md border border-slate-700">
                                <label class="block text-sm font-medium text-gray-300 mb-1">Starting Code Template</label>
                                <textarea name="sandbox_code" id="sandbox_code" rows="10" 
                                          class="block w-full rounded-md border-gray-600 bg-slate-800 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border code-input"
                                          spellcheck="false"><?php echo "<?php\n\n// Write your code here..."; ?></textarea>
                                <p class="text-xs text-gray-500 mt-2">Press <strong>Tab</strong> to indent.</p>
                            </div>
                        </div>

                        <div class="pt-6 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Lesson
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle logic
            const select = document.getElementById('activity_type');
            const sections = document.querySelectorAll('.activity-section');
            const introSection = document.getElementById('intro-content-section');

            function toggleSections() {
                const selected = select.value;
                sections.forEach(section => {
                    if (section.id === 'section-' + selected) {
                        section.classList.remove('hidden');
                    } else {
                        section.classList.add('hidden');
                    }
                });
                // Hide intro content for text_only, show for others
                if (selected === 'text_only') {
                    introSection.classList.add('hidden');
                } else {
                    introSection.classList.remove('hidden');
                }
            }

            select.addEventListener('change', toggleSections);
            toggleSections(); // Initial toggle

            // Tab logic
            const codeTextarea = document.getElementById('sandbox_code');
            if (codeTextarea) {
                codeTextarea.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        e.preventDefault();
                        const start = this.selectionStart;
                        const end = this.selectionEnd;
                        this.value = this.value.substring(0, start) + "    " + this.value.substring(end);
                        this.selectionStart = this.selectionEnd = start + 4;
                    }
                });
            }
        });
    </script>
</body>
</html>