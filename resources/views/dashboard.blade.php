<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6 p-6">
                <h3 class="text-lg font-semibold text-gray-700">Total Donasi yang Diterima</h3>
                <p class="text-2xl font-bold text-green-600">
                    Rp <span id="total-donation">{{ number_format(Auth::user()->donations()->where
                    ('status',
                        'completed')->sum('amount'), 0, '.', '.') }}</span>
                    <span id="donation-update"
                        class="text-lg font-semibold text-green-500 transition-opacity opacity-0"></span>
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6 p-6">
                <label for="profile-url" class="block text-sm font-medium text-gray-700">Profile URL</label>
                <div class="flex mt-1">
                    <input type="text" id="profile-url" value="{{ url('user/' . Auth::user()->username) }}"
                        class="form-input block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 
                        focus:ring focus:ring-indigo-500 focus:ring-opacity-50" readonly>
                    <button onclick="copyProfileUrl()"
                        class="ml-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md 
                        hover:bg-indigo-700 focus:outline-none focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                        Copy
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyProfileUrl() {
                const profileUrl = document.getElementById('profile-url');
                profileUrl.select();
                profileUrl.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(profileUrl.value);
                alert('Copied to clipboard');
            }
    </script>
    @endpush

    @push('script-head')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const userId = {{ Auth::id() }};
        const totalDonationEl = document.getElementById('total-donation');
        const donationUpdateEl = document.getElementById('donation-update');

        window.Echo.private(`donations.user.${userId}`)
            .listen('.donation.received', (data) => {
                console.log("EVENT MASUK:", data);

                const donation = data.donation;
                const newAmount = parseInt(donation.amount);

                let currentTotal = parseInt(totalDonationEl.innerText.replace(/\D/g, ''));
                let updatedTotal = currentTotal + newAmount;

                donationUpdateEl.innerText = `+ Rp ${newAmount.toLocaleString("id-ID")}`;
                donationUpdateEl.style.opacity = 1;
                donationUpdateEl.style.transition = 'opacity 0.5s ease-in-out';

                setTimeout(() => {
                    donationUpdateEl.style.opacity = 0;
                }, 10000);

                totalDonationEl.innerText = updatedTotal.toLocaleString();

                Swal.fire({
                    title: '✨ Donasi Baru Diterima!',
                    html: `
                        <p><strong>Rp ${newAmount.toLocaleString("id-ID")}</strong> dari 
                        <strong>${donation.name}</strong></p>
                        <p>${donation.message}</p>
                    `,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 5000
                });
            });


    });
    </script>
    @endpush

</x-app-layout>
