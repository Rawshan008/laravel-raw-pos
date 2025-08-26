<div>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-3" icon="heroicon-m-sparkles">
            Save
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
