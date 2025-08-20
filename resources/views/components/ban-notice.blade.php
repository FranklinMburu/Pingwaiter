@props(['banReason', 'contact'])
<div class="w-full bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 flex flex-col md:flex-row items-start md:items-center" role="alert" aria-live="assertive">
    <svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414-1.414A9 9 0 105.636 18.364l1.414 1.414A9 9 0 1018.364 5.636z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" />
    </svg>
    <div class="flex-1">
        <span class="font-bold text-lg">Account Banned</span>
        <p class="mt-1 text-base">{{ $banReason ?? 'You have been banned from ordering.' }}</p>
        <p class="mt-1 text-sm">For assistance, contact <a href="mailto:{{ $contact }}" class="underline text-red-800">{{ $contact }}</a></p>
    </div>
</div>
