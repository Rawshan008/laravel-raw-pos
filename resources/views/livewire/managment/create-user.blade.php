<div>
    <form wire:submit="submit">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-3" icon="heroicon-m-sparkles">
            Submit
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>
