@if ($faq_questions)
<section style="background: #eeeeee;">
    <div class="container">
        <div class="section-title">
            <h2>Perguntas frequentes</h2>
            <p>FAQS</p>
        </div>
        <div class="accordion" id="accordionExample">
            @foreach ($faq_questions as $faq_question)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-{{ $faq_question->id }}" aria-expanded="false"
                        aria-controls="collapse-{{ $faq_question->id }}">
                        {{ $faq_question->question }}
                    </button>
                </h2>
                <div id="collapse-{{ $faq_question->id }}" class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        {{ $faq_question->answer }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <hr>
        <div class="mt-4">
            <h4 class="pb-4">Quais as Condições necessárias para conduzir veículos TVDE?</h4>
            <a href="/cms/{{ $page->id }}/{{ $page->slug }}" class="btn-theme">Conhecer as condições</a>
        </div>
    </div>
</section>
@endif