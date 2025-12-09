@extends('layouts.buyer')

@section('title', 'My Profile - WALKUNO')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-4xl">
        
        <!-- Flash Message -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Success</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <p class="font-bold">Update Failed</p>
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#1E3A8A] to-[#60A5FA] px-8 py-10 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-white opacity-5 rounded-full -ml-12 -mb-12"></div>
                
                <h1 class="font-heading font-black text-3xl md:text-4xl relative z-10">My Profile</h1>
                <p class="text-blue-100 mt-2 relative z-10">Manage your account information and preferences</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Profile Picture Section -->
                    <div class="md:col-span-1 flex flex-col items-center space-y-4">
                        <div class="relative group">
                            <div id="profile-picture-frame" class="w-40 h-40 rounded-full border-4 border-white shadow-lg overflow-hidden bg-gray-200 aspect-square relative">
                                @if(Auth::user()->buyer && Auth::user()->buyer->profile_picture)
                                    <img id="preview-image" src="{{ asset('storage/' . Auth::user()->buyer->profile_picture) }}" alt="Profile Picture" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-[#60A5FA] to-[#1E3A8A] text-white text-5xl font-black">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                
                                <!-- Overlay for upload -->
                                <label for="profile_picture" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-10">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </label>
                            </div>
                            <span class="text-xs text-gray-400 mt-2 text-center block">Click image to change</span>
                        </div>
                        <input type="file" id="profile_picture" name="profile_picture" class="hidden" accept="image/*" onchange="previewImage(this)">
                    </div>

                    <!-- Form Fields -->
                    <div class="md:col-span-2 space-y-6">
                        
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#60A5FA] focus:ring focus:ring-[#60A5FA]/20 outline-none transition-all">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#60A5FA] focus:ring focus:ring-[#60A5FA]/20 outline-none transition-all">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->buyer->phone_number ?? '') }}" placeholder="e.g. 08123456789"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#60A5FA] focus:ring focus:ring-[#60A5FA]/20 outline-none transition-all">
                        </div>

                        <!-- Actions -->
                        <div class="pt-6 flex items-center justify-end gap-4 border-t border-gray-100">
                            <a href="{{ route('home') }}" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition-colors">Cancel</a>
                            <button type="submit" class="px-8 py-2.5 rounded-lg bg-gradient-to-r from-[#1E3A8A] to-[#60A5FA] text-white font-bold hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                                Save Changes
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                // Select specific ID to avoid conflict with other circular elements
                var container = document.getElementById('profile-picture-frame');
                
                // Remove existing content but KEEP the overlay label (which is absolutely positioned and needed for re-upload)
                // Actually, the label is inside the container. We should only replace the image/div part.
                
                // Strategy: Find the img or the initial div and replace IT.
                var existingImg = container.querySelector('img#preview-image');
                var existingInitial = container.querySelector('div.bg-gradient-to-br');
                
                if (existingImg) {
                    existingImg.src = e.target.result;
                } else if (existingInitial) {
                    // Create new image element
                    var newImg = document.createElement('img');
                    newImg.id = "preview-image";
                    newImg.src = e.target.result;
                    newImg.alt = "Profile Picture";
                    newImg.className = "w-full h-full object-cover absolute inset-0"; // Ensure it fills container
                    
                    // Replace initial div with new image
                    container.replaceChild(newImg, existingInitial);
                } else {
                    // Fallback: Just prepend it if something is weird
                     var newImg = document.createElement('img');
                    newImg.id = "preview-image";
                    newImg.src = e.target.result;
                    newImg.alt = "Profile Picture";
                    newImg.className = "w-full h-full object-cover absolute inset-0";
                    container.insertBefore(newImg, container.firstChild);
                }
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
