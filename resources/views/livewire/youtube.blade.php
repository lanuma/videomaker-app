<div>
    @auth
        <label for="channel" class="block text-sm font-medium text-gray-700">Select Channel</label>
        <select name="channel" wire:model="channel" wire:change="channel($event.target.value)" class="mt-2 mb-3 py-2 px-3 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            <option value="">.::Pilih::.</option>
            @foreach ($channels as $channel)
            <option value="{{ $channel->channel_id }}">{{ $channel->channel_name }}</option>
            @endforeach
        </select>
    @endauth

    @if(session()->has('youtube-token'))

    <form wire:submit.prevent="submit">
        @csrf
        <div>

            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" name="title" value="" wire:model="title" placeholder="Youtube Video Title" class="mt-2 mb-3 py-2 px-3 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">

            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea type="text" name="description" value="" wire:model="description" placeholder="Youtube Video Description" class="mt-2 mb-3 py-2 px-3 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>

            <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
            <input type="text" name="tags" value="" wire:model="tags" placeholder="Tags separated with comma" class="mt-2 mb-3 py-2 px-3 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">

            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category" wire:model="category" class="mt-2 mb-3 py-2 px-3 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <option value="">.::Pilih::.</option>
                @foreach ($videoCategories as $category)
                <option value="{{ $category->id }}">{{ $category->snippet->title }}</option>
                @endforeach
            </select>

            {{-- @dd($  ) --}}

            <label for="playlists" class="block text-sm font-medium text-gray-700">Playlists</label>
            <select name="playlists" wire:model="playlist" class="mt-2 mb-3 py-2 px-3 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @forelse($playlists as $playlist)
                <option value="{{ $playlist->id }}">{{ $playlist->snippet->title }} ({{ $playlist->status->privacyStatus }})</option>
                @empty
                <option value="">Tidak ditemukan</option>
                @endforelse
            </select>

            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" wire:model="status" class="mt-2 mb-3 py-2 px-3 border focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <option value="public">Public</option>
                <option value="private">Private</option>
                <option value="unlisted">Unlisted</option>
            </select>

            <label for="video" class="block text-sm font-medium text-gray-700">Upload</label>
            <input type="file" name="video" id="video" wire:model="video">

            <input type="submit" wire:submit class="my-5 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        </div>

    </form>

    @endif
</div>
