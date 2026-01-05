@php
    $counters = $data['counters'] ?? collect();
    $title = $data['title'] ?? null;
    $background = $data['background'] ?? 'gradient';
@endphp

<section class="py-20 {{ $background === 'gradient' ? 'gradient-primary' : 'bg-gray-900' }} text-white relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><circle cx=\"50\" cy=\"50\" r=\"1\" fill=\"white\"/></svg>'); background-size: 50px 50px;"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        @if($title)
            <div class="text-center mb-12" data-animate="animate-fade-in-up">
                <h2 class="text-3xl md:text-4xl font-bold">{{ $title }}</h2>
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            @foreach($counters as $index => $counter)
                <div class="text-center" data-animate="animate-scale-in" data-delay="{{ $index * 100 }}">
                    @if($counter->icon)
                        <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="{{ $counter->icon }} text-2xl"></i>
                        </div>
                    @endif

                    <div class="text-4xl md:text-5xl font-bold mb-2 counter" data-target="{{ $counter->value }}">0</div>
                    <div class="text-lg text-white/80">{{ $counter->title }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter');
        const speed = 200;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.getAttribute('data-target');
                    const suffix = '{{ $counters->first()?->suffix ?? "" }}';

                    const updateCount = () => {
                        const count = +counter.innerText.replace(/[^0-9]/g, '');
                        const inc = target / speed;

                        if (count < target) {
                            counter.innerText = Math.ceil(count + inc) + suffix;
                            setTimeout(updateCount, 10);
                        } else {
                            counter.innerText = target.toLocaleString() + suffix;
                        }
                    };

                    updateCount();
                    observer.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(counter => observer.observe(counter));
    });
</script>
