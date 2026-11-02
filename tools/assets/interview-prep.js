(function () {
    'use strict';
    var professionSearch = document.getElementById('ivProfessionSearch');
    var professionCards = Array.prototype.slice.call(document.querySelectorAll('[data-profession]'));
    var allProfessions = document.getElementById('ivAllProfessions');
    var showAll = false;
    function filterProfessions() {
        var query = professionSearch.value.trim().toLocaleLowerCase();
        var matches = professionCards.filter(function (card) { return card.getAttribute('data-profession').toLocaleLowerCase().indexOf(query) !== -1; });
        professionCards.forEach(function (card) { card.hidden = matches.indexOf(card) === -1 || (!query && !showAll && matches.indexOf(card) >= 6); });
        document.getElementById('ivProfessionEmpty').hidden = matches.length !== 0;
        document.getElementById('ivProfessionCount').textContent = matches.length + ' professions found.';
        allProfessions.hidden = !!query || showAll || matches.length <= 6;
    }
    if (professionSearch) {
        professionSearch.addEventListener('input', filterProfessions);
        professionSearch.form.addEventListener('submit', function (event) { event.preventDefault(); filterProfessions(); });
        allProfessions.addEventListener('click', function () { showAll = true; filterProfessions(); });
        filterProfessions();
    }
    var questionSearch = document.getElementById('ivQuestionSearch');
    var more = document.getElementById('ivMoreQuestions');
    if (questionSearch) {
        var rows = Array.prototype.slice.call(document.querySelectorAll('#ivQuestionList .iv-question'));
        var filters = Array.prototype.slice.call(document.querySelectorAll('[data-filter]'));
        var type = document.getElementById('ivTypeInput').value;
        var limit = 4;
        function filterQuestions(reset) {
            if (reset) { limit = 4; }
            var query = questionSearch.value.trim().toLocaleLowerCase();
            var matches = rows.filter(function (row) { return (type === 'all' || row.getAttribute('data-type') === type) && row.textContent.toLocaleLowerCase().indexOf(query) !== -1; });
            rows.forEach(function (row) { row.hidden = matches.indexOf(row) === -1 || matches.indexOf(row) >= limit; });
            if (reset && matches[0]) { matches[0].open = true; }
            more.hidden = matches.length <= limit;
            document.getElementById('ivQuestionEmpty').hidden = matches.length !== 0;
            document.getElementById('ivQuestionCount').textContent = Math.min(limit, matches.length) + ' of ' + matches.length + ' questions shown.';
            filters.forEach(function (filter) {
                var active = filter.getAttribute('data-filter') === type;
                filter.classList.toggle('is-active', active);
                if (active) { filter.setAttribute('aria-current', 'true'); } else { filter.removeAttribute('aria-current'); }
            });
        }
        filters.forEach(function (filter) { filter.addEventListener('click', function (event) { event.preventDefault(); type = filter.getAttribute('data-filter'); document.getElementById('ivTypeInput').value = type; filterQuestions(true); }); });
        questionSearch.addEventListener('input', function () { filterQuestions(true); });
        questionSearch.form.addEventListener('submit', function (event) { event.preventDefault(); filterQuestions(true); });
        more.setAttribute('aria-controls', 'ivQuestionList');
        more.addEventListener('click', function () {
            var previous = rows.filter(function (row) { return !row.hidden; });
            limit += 4;
            filterQuestions(false);
            var newlyShown = rows.filter(function (row) { return !row.hidden && previous.indexOf(row) === -1; });
            if (newlyShown.length) {
                var summary = newlyShown[0].querySelector('summary');
                summary.focus({ preventScroll: true });
                newlyShown[0].scrollIntoView({ block: 'start', behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
            }
        });
        filterQuestions(true);
    }
    var start = document.getElementById('ivMockStart');
    var questions = window.OHSC_IV_PRACTICE || [];
    if (start && questions.length) {
        var board = document.getElementById('ivMockBoard');
        var answer = document.getElementById('ivMockAnswer');
        var reveal = document.getElementById('ivMockReveal');
        var next = document.getElementById('ivMockNext');
        var index = 0;
        function renderMock() {
            document.getElementById('ivMockProgress').textContent = 'Question ' + (index + 1) + ' of ' + questions.length;
            document.getElementById('ivMockQuestion').textContent = questions[index].question;
            answer.textContent = questions[index].answer;
            answer.hidden = true;
            reveal.textContent = 'Show sample guidance';
            reveal.setAttribute('aria-expanded', 'false');
            next.textContent = index === questions.length - 1 ? 'Start again' : 'Next Question →';
        }
        reveal.setAttribute('aria-controls', 'ivMockAnswer');
        start.addEventListener('click', function () { index = 0; renderMock(); board.hidden = false; board.scrollIntoView({block:'nearest'}); reveal.focus(); });
        reveal.addEventListener('click', function () { answer.hidden = !answer.hidden; reveal.textContent = answer.hidden ? 'Show sample guidance' : 'Hide sample guidance'; reveal.setAttribute('aria-expanded', String(!answer.hidden)); });
        next.addEventListener('click', function () { index = (index + 1) % questions.length; renderMock(); });
        document.getElementById('ivMockEnd').addEventListener('click', function () { board.hidden = true; start.focus(); });
    }
})();
