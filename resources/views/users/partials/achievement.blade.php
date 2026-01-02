@if(session('new_achievement'))
    @php
        $achievement = session('new_achievement');
    @endphp
    
    <!-- Steam-Style Achievement Popup -->
    <div id="steam-popup" class="fixed bottom-10 right-5 z-[110] transform translate-y-32 opacity-0 transition-all duration-500 ease-out cursor-pointer" style="display: none; backdrop-filter: none !important;">
        <div class="flex items-center w-80 bg-[#1b2838] border-2 border-[#3e5369] rounded shadow-2xl overflow-hidden relative group">
            <!-- Icon Section -->
            <div class="w-20 h-20 flex-shrink-0 bg-gray-900 flex items-center justify-center border-r border-[#3e5369]">
                @if($achievement->icon_path && file_exists(public_path($achievement->icon_path)))
                     <img src="{{ asset($achievement->icon_path) }}" class="w-12 h-12" alt="Icon">
                @else
                     <!-- Default Trophy Icon -->
                     <svg class="h-12 w-12 text-[#66c0f4]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.699-3.177a1 1 0 111.44 1.414l-1.698 3.177L19.5 9a1 1 0 110 2l-3.105 1.68 1.698 3.177a1 1 0 11-1.44 1.414L14.954 14.09 11 15.677V17a1 1 0 11-2 0v-1.323l-3.954-1.582-1.699 3.177a1 1 0 11-1.44-1.414l1.698-3.177L1.5 11a1 1 0 110-2l3.105-1.68-1.698-3.177a1 1 0 111.44-1.414L5.046 5.91 9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                @endif
            </div>
            
            <!-- Text Section -->
            <div class="flex-1 px-4 py-3">
                <div class="text-[10px] text-gray-400 uppercase font-bold tracking-wider mb-0.5">Achievement Unlocked</div>
                <div class="text-base text-white font-bold leading-tight mb-1">{{ $achievement->name }}</div>
                <div class="text-xs text-gray-400 leading-snug">{{ $achievement->description }}</div>
            </div>

            <!-- Close Button (Visible on Hover) -->
            <button id="close-steam-popup" class="absolute top-1 right-1 text-gray-500 hover:text-white opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('steam-popup');
            const closeBtn = document.getElementById('close-steam-popup');
            
            if (popup) {
                // Show popup
                popup.style.display = 'block';
                
                // Animate In (Small delay to allow display:block to render)
                setTimeout(() => {
                    popup.classList.remove('translate-y-32', 'opacity-0');
                }, 100);

                // Auto Dismiss Timer (5 seconds)
                let dismissTimer = setTimeout(dismissPopup, 5000);

                // Close Button Logic
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation(); // Prevent bubbling
                    clearTimeout(dismissTimer);
                    dismissPopup();
                });

                // Dismiss Function
                function dismissPopup() {
                    popup.classList.add('translate-y-32', 'opacity-0');
                    setTimeout(() => {
                        popup.style.display = 'none';
                    }, 500); // Wait for transition to finish
                }
            }
        });
    </script>
@endif