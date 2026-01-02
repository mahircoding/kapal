<x-layouts.app title="Admin Settings">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="relative mb-6 w-full">
                        <flux:heading size="xl" level="1">{{ __('Admin Settings') }}</flux:heading>
                        <flux:subheading size="lg" class="mb-6">{{ __('Manage system configurations and integrations') }}</flux:subheading>
                        <flux:separator variant="subtle" />
                    </div>

                    <div class="flex items-start max-md:flex-col">
                        
                        <!-- Sidebar / Tabs -->
                        <div class="me-10 w-full pb-4 md:w-[220px]">
                            <flux:navlist>
                                <flux:navlist.item :href="route('admin.settings.index', ['tab' => 'whatsapp'])" :current="request()->query('tab', 'whatsapp') === 'whatsapp'" wire:navigate>{{ __('WhatsApp') }}</flux:navlist.item>
                                <flux:navlist.item :href="route('admin.settings.index', ['tab' => 'smtp'])" :current="request()->query('tab') === 'smtp'" wire:navigate>{{ __('SMTP Mail') }}</flux:navlist.item>
                                <flux:navlist.item :href="route('admin.settings.index', ['tab' => 'notifications'])" :current="request()->query('tab') === 'notifications'" wire:navigate>{{ __('Notifications') }}</flux:navlist.item>
                                <flux:navlist.item :href="route('admin.settings.index', ['tab' => 'appearance'])" :current="request()->query('tab') === 'appearance'" wire:navigate>{{ __('Appearance') }}</flux:navlist.item>
                            </flux:navlist>
                        </div>

                        <flux:separator class="md:hidden" />

                        <div class="flex-1 self-stretch max-md:pt-6">
                            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="w-full max-w-lg space-y-6">
                                @csrf
                                @method('PUT')

                                <!-- WhatsApp Tab -->
                                @if(request()->query('tab', 'whatsapp') === 'whatsapp')
                                <div>
                                    <flux:heading>{{ __('WhatsApp Configuration') }}</flux:heading>
                                    <flux:subheading>{{ __('Configure WhatsApp API for OTP delivery') }}</flux:subheading>
                                    
                                    <div class="mt-5 space-y-6">
                                        <flux:input 
                                            name="whatsapp_api_key" 
                                            label="WhatsApp API Key" 
                                            value="{{ old('whatsapp_api_key', $apiKey) }}" 
                                            description="Enter your StarSender API key here."
                                        />
                                        @error('whatsapp_api_key')
                                            <flux:error>{{ $message }}</flux:error>
                                        @enderror
                                    </div>
                                </div>
                                @endif

                                <!-- SMTP Tab -->
                                @if(request()->query('tab') === 'smtp')
                                <div>
                                    <flux:heading>{{ __('SMTP Configuration') }}</flux:heading>
                                    <flux:subheading>{{ __('Configure email server settings') }}</flux:subheading>

                                    <div class="mt-5 space-y-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <flux:input name="mail_host" label="Mail Host" value="{{ old('mail_host', $mailHost) }}" />
                                            <flux:input name="mail_port" label="Mail Port" type="number" value="{{ old('mail_port', $mailPort) }}" />
                                        </div>
                                        
                                        <flux:input name="mail_username" label="Username" value="{{ old('mail_username', $mailUsername) }}" />
                                        <flux:input name="mail_password" label="Password" type="text" value="{{ old('mail_password', $mailPassword) }}" />
                                        
                                        
                                        <flux:select name="mail_encryption" label="Encryption">
                                            <option value="">None</option>
                                            <option value="tls" {{ old('mail_encryption', $mailEncryption) === 'tls' ? 'selected' : '' }}>TLS</option>
                                            <option value="ssl" {{ old('mail_encryption', $mailEncryption) === 'ssl' ? 'selected' : '' }}>SSL</option>
                                        </flux:select>
                                        
                                        <flux:input name="mail_from_address" label="From Address" type="email" value="{{ old('mail_from_address', $mailFromAddress) }}" />
                                        <flux:input name="mail_from_name" label="From Name" value="{{ old('mail_from_name', $mailFromName) }}" />
                                    </div>
                                </div>
                                @endif

                                <!-- Notifications Tab -->
                                @if(request()->query('tab') === 'notifications')
                                <div>
                                    <flux:heading>{{ __('Notification Settings') }}</flux:heading>
                                    <flux:subheading>{{ __('Configure admin contacts for application notifications') }}</flux:subheading>

                                    <div class="mt-5 space-y-6">
                                        <flux:input 
                                            name="admin_notification_email" 
                                            label="Admin Email" 
                                            type="email"
                                            value="{{ old('admin_notification_email', $adminNotificationEmail ?? '') }}" 
                                            description="Email address to receive notifications for new registrations and job applications"
                                        />
                                        @error('admin_notification_email')
                                            <flux:error>{{ $message }}</flux:error>
                                        @enderror

                                        <flux:input 
                                            name="admin_notification_whatsapp" 
                                            label="Admin WhatsApp Number" 
                                            type="text"
                                            value="{{ old('admin_notification_whatsapp', $adminNotificationWhatsapp ?? '') }}" 
                                            description="WhatsApp number to receive notifications (format: 628xxxxxxxxxx)"
                                            placeholder="628123456789"
                                        />
                                        @error('admin_notification_whatsapp')
                                            <flux:error>{{ $message }}</flux:error>
                                        @enderror

                                        <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                                <strong>Note:</strong> Notifications will be sent to these contacts when:
                                            </p>
                                            <ul class="list-disc list-inside text-sm text-blue-700 dark:text-blue-300 mt-2 space-y-1">
                                                <li>A new user registers an account</li>
                                                <li>A job application is submitted</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Appearance Tab -->
                                @if(request()->query('tab') === 'appearance')
                                <div>
                                    <flux:heading>{{ __('Appearance Settings') }}</flux:heading>
                                    <flux:subheading>{{ __('Customize your website branding') }}</flux:subheading>

                                    <div class="mt-5 space-y-6">
                                        <flux:input 
                                            name="site_name" 
                                            label="Site Name" 
                                            value="{{ old('site_name', $siteName) }}" 
                                            description="The name of your website"
                                        />
                                        @error('site_name')
                                            <flux:error>{{ $message }}</flux:error>
                                        @enderror

                                        <flux:textarea 
                                            name="site_tagline" 
                                            label="Site Tagline" 
                                            rows="2"
                                            description="A short description or tagline for your website"
                                        >{{ old('site_tagline', $siteTagline) }}</flux:textarea>
                                        @error('site_tagline')
                                            <flux:error>{{ $message }}</flux:error>
                                        @enderror

                                        <div>
                                            <flux:label>Site Logo</flux:label>
                                            <flux:description>Upload your website logo (PNG, JPG, SVG - Max 2MB)</flux:description>
                                            @if($siteLogo)
                                                <div class="mt-2 mb-3">
                                                    <img src="{{ Storage::url($siteLogo) }}" alt="Current Logo" class="h-16 object-contain">
                                                    <p class="text-sm text-gray-500 mt-1">Current logo</p>
                                                </div>
                                            @endif
                                            <input type="file" name="site_logo" accept="image/png,image/jpeg,image/svg+xml" class="mt-2">
                                            @error('site_logo')
                                                <flux:error>{{ $message }}</flux:error>
                                            @enderror
                                        </div>

                                        <div>
                                            <flux:label>Site Favicon</flux:label>
                                            <flux:description>Upload your website favicon (PNG, ICO - Max 1MB)</flux:description>
                                            @if($siteFavicon)
                                                <div class="mt-2 mb-3">
                                                    <img src="{{ Storage::url($siteFavicon) }}" alt="Current Favicon" class="h-8 object-contain">
                                                    <p class="text-sm text-gray-500 mt-1">Current favicon</p>
                                                </div>
                                            @endif
                                            <input type="file" name="site_favicon" accept="image/png,image/x-icon,image/vnd.microsoft.icon" class="mt-2">
                                            @error('site_favicon')
                                                <flux:error>{{ $message }}</flux:error>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="flex justify-end pt-4">
                                    <flux:button variant="primary" type="submit">{{ __('Save Changes') }}</flux:button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
