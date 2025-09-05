<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Franchise Opportunity - ENI Platform</title>
    <meta name="theme-color" content="#FFCD00">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'eni-dark': '#1a1a1a',
                        'eni-yellow': '#FFCD00'
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-eni-dark min-h-screen text-white">
<div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-eni-yellow hover:text-white mb-4 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
            <h1 class="text-3xl font-bold text-white">ENI Fuel Station Franchise</h1>
            <p class="mt-2 text-gray-400">Open your own ENI service station</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-900/50 border border-green-600 text-green-200 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-900/50 border border-red-600 text-red-200 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Franchise Information -->
            <div class="bg-gray-900 border border-eni-yellow/20 rounded-2xl p-6">
                <h2 class="text-2xl font-bold text-eni-yellow mb-4">Why Open an ENI Station?</h2>
                
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="bg-eni-yellow text-eni-dark rounded-full p-2 mt-1 flex-shrink-0">
                            <i class="fas fa-chart-line w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Proven Business Model</h3>
                            <p class="text-gray-400 text-sm">Join a successful fuel retail network with decades of experience and market presence.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="bg-eni-yellow text-eni-dark rounded-full p-2 mt-1 flex-shrink-0">
                            <i class="fas fa-tools w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Complete Support</h3>
                            <p class="text-gray-400 text-sm">Site selection, construction guidance, training, and ongoing operational support.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="bg-eni-yellow text-eni-dark rounded-full p-2 mt-1 flex-shrink-0">
                            <i class="fas fa-gas-pump w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Quality Products</h3>
                            <p class="text-gray-400 text-sm">Premium fuel and automotive products backed by ENI's reputation for quality.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3">
                        <div class="bg-eni-yellow text-eni-dark rounded-full p-2 mt-1 flex-shrink-0">
                            <i class="fas fa-map-marker-alt w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Territory Protection</h3>
                            <p class="text-gray-400 text-sm">Protected territory rights ensuring minimal competition from other ENI stations.</p>
                        </div>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <h3 class="font-semibold text-eni-yellow mb-3">Franchise Requirements</h3>
                    <ul class="text-sm text-gray-400 space-y-2">
                        <li>• Minimum investment: $150,000 - $300,000</li>
                        <li>• Suitable land or property for fuel station</li>
                        <li>• Business experience in retail or service industry</li>
                        <li>• Commitment to ENI brand standards and procedures</li>
                        <li>• Valid licenses for fuel retail in your area</li>
                    </ul>
                </div>
            </div>

            <!-- Application Form -->
            <div class="bg-gray-900 border border-eni-yellow/20 rounded-2xl p-6">
                @if($existingApplication)
                    <h2 class="text-2xl font-bold text-eni-yellow mb-4">Your Application Status</h2>
                    <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-white">Application Status:</span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $existingApplication->status === 'approved' ? 'bg-green-900 border border-green-600 text-green-400' : 
                                   ($existingApplication->status === 'rejected' ? 'bg-red-900 border border-red-600 text-red-400' : 'bg-eni-yellow/20 border border-eni-yellow text-eni-yellow') }}">
                                {{ ucfirst($existingApplication->status) }}
                            </span>
                        </div>
                        <div class="text-sm text-gray-400">
                            <p><strong>Station Location:</strong> ENI {{ $existingApplication->company_name }}</p>
                            <p><strong>Franchisee:</strong> {{ $existingApplication->contact_person }}</p>
                            <p><strong>Applied:</strong> {{ $existingApplication->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    
                    @if($existingApplication->status === 'pending')
                        <p class="text-gray-400">Your fuel station application is currently under review. Our franchise team will contact you soon!</p>
                    @elseif($existingApplication->status === 'approved')
                        <div class="bg-green-900/50 border border-green-600 text-green-200 px-4 py-3 rounded-lg">
                            <p class="font-medium">Congratulations! Your fuel station franchise application has been approved.</p>
                            <p class="text-sm mt-1">Our franchise development team will contact you shortly to begin the setup process.</p>
                        </div>
                    @elseif($existingApplication->status === 'rejected')
                        <div class="bg-red-900/50 border border-red-600 text-red-200 px-4 py-3 rounded-lg mb-4">
                            <p>Unfortunately, your previous fuel station application was not approved at this time.</p>
                            <p class="text-sm mt-1">You may submit a new application after addressing any concerns.</p>
                        </div>
                    @endif
                @else
                    <h2 class="text-2xl font-bold text-eni-yellow mb-4">Submit Your Application</h2>
                    
                    <form action="{{ route('dashboard.franchise.process') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label for="company_name" class="block text-sm font-medium mb-2 text-white">Station Location Identifier *</label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
                                   class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-400 focus:border-eni-yellow focus:outline-none focus:ring-1 focus:ring-eni-yellow"
                                   placeholder="e.g., Downtown, Highway 95, Mall Plaza">
                            <p class="text-xs text-gray-500 mt-1">This will be used as: "ENI [Your Identifier]" (e.g., ENI Downtown)</p>
                        </div>

                        <div>
                            <label for="contact_person" class="block text-sm font-medium mb-2 text-white">Franchisee Name *</label>
                            <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}" required
                                   class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-400 focus:border-eni-yellow focus:outline-none focus:ring-1 focus:ring-eni-yellow">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium mb-2 text-white">Contact Email *</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-400 focus:border-eni-yellow focus:outline-none focus:ring-1 focus:ring-eni-yellow">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium mb-2 text-white">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                   class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-400 focus:border-eni-yellow focus:outline-none focus:ring-1 focus:ring-eni-yellow">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium mb-2 text-white">Proposed Station Location *</label>
                            <textarea id="address" name="address" rows="3" required
                                      class="w-full px-4 py-3 rounded-lg bg-gray-800 border border-gray-600 text-white placeholder-gray-400 focus:border-eni-yellow focus:outline-none focus:ring-1 focus:ring-eni-yellow"
                                      placeholder="Full address of proposed fuel station location">{{ old('address') }}</textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-eni-yellow text-eni-dark font-bold py-3 rounded-lg hover:bg-yellow-400 transition-colors">
                                Submit Franchise Application
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Contact Information -->
        <div class="mt-8 bg-gray-900 border border-eni-yellow/20 rounded-2xl p-6">
            <h3 class="text-xl font-bold text-eni-yellow mb-4">Questions About Fuel Station Franchise?</h3>
            <div class="grid md:grid-cols-3 gap-4 text-sm">
                <div class="text-center">
                    <div class="bg-eni-yellow text-eni-dark rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-phone"></i>
                    </div>
                    <p class="font-medium text-white">Call Us</p>
                    <p class="text-gray-400">+1 (555) 123-4567</p>
                </div>
                <div class="text-center">
                    <div class="bg-eni-yellow text-eni-dark rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <p class="font-medium text-white">Email</p>
                    <p class="text-gray-400">franchise@eni.com</p>
                </div>
                <div class="text-center">
                    <div class="bg-eni-yellow text-eni-dark rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <p class="font-medium text-white">Schedule Meeting</p>
                    <p class="text-gray-400">Site visit consultation</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Footer -->
    @include('components.footer')

    <!-- Footer Modals -->
    @include('components.footer-modals')
</body>
</html>
