<div class="relative ml-4">
    <button onclick="toggleLangMenu()" class="flex items-center gap-1 bg-white border border-gray-300 rounded-md px-2 py-2 hover:bg-gray-50 focus:outline-none shadow-sm">

        <img src="{{ config('languages.'.app()->getLocale().'.flag') }}" alt="flag" class="w-6 h-auto object-cover border border-gray-200">

        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <div id="lang-menu" class="hidden absolute right-0 mt-2 w-auto min-w-[max-content] rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-hidden">
        <div class="py-1">

            @foreach(config('languages') as $code => $content)
                <a href="{{ route('set_lang', $code) }}" class="block px-3 py-2 hover:bg-gray-100 transition duration-150 ease-in-out" title="{{ $content['name'] }}">
                    <img src="{{ $content['flag'] }}" alt="{{ $content['name'] }}" class="w-6 h-auto object-cover border border-gray-200 shadow-sm">
                </a>
            @endforeach

        </div>
    </div>
</div>

<script>
    function toggleLangMenu() {
        document.getElementById('lang-menu').classList.toggle('hidden');
    }
    // Cierra el menú si haces clic fuera
    window.addEventListener('click', function(e) {
        const button = document.querySelector('button[onclick="toggleLangMenu()"]');
        const menu = document.getElementById('lang-menu');
        if (!button.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
