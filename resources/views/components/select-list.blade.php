<div x-data="selectComponent()" x-init="init()">
    <div wire:ignore class="w-full">
        <select x-ref="select" class="select2 w-full" data-placeholder="Select question" {{ $attributes }}>
            @if (!isset($attributes['multiple']))
                <option></option>
            @endif
            @foreach ($options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
</div>

@push('scripts')
    <script>
        function selectComponent() {
            return {
                modelName: '{{ $attributes['wire:model.live'] }}',
                isMultiple: {{ isset($attributes['multiple']) ? 'true' : 'false' }},
                
                init() {
                    this.initSelect()
                    
                    // Listen for Livewire updates
                    this.$wire.$hook('morph.updated', () => {
                        this.initSelect()
                    });
                },
                
                initSelect() {
                    $(this.$refs.select).select2({
                        placeholder: 'Select question',
                        allowClear: !$(this.$refs.select).attr('required')
                    })
                    
                    // Set initial values
                    let initialValue = this.$wire.get(this.modelName)
                    if (initialValue) {
                        $(this.$refs.select).val(initialValue).trigger('change.select2')
                    }
                    
                    // Handle changes from Select2
                    $(this.$refs.select).on('change', (e) => {
                        let data = $(this.$refs.select).select2("val")
                        if (data === "" || data === null) {
                            data = this.isMultiple ? [] : null
                        }
                        this.$wire.set(this.modelName, data)
                    });
                }
            }
        }
    </script>
@endpush
