<!-- Try Demo Button Partial -->
<button
    x-data="{ loading: false }"
    x-on:click="loading = true"
    x-bind:disabled="loading"
    x-bind:aria-busy="loading"
    aria-label="Try Demo"
    role="button"
    type="button"
    class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold shadow-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hover:from-indigo-700 hover:to-purple-700 disabled:opacity-60 disabled:cursor-not-allowed text-base md:text-lg"
    @click.prevent="loading = true; window.location.href = '/demo-login'"
>
    <svg x-show="!loading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m4 4h-1a4 4 0 01-4-4V7a4 4 0 014-4h1a4 4 0 014 4v5a4 4 0 01-4 4z" />
    </svg>
    <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
    </svg>
    <span x-text="loading ? 'Loading...' : 'Try Demo'">Try Demo</span>
</button>
<!-- Usage: -->
