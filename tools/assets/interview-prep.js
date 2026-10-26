(function () {
    var data = window.OHSC_IV || { industries: [] };
    var industries = data.industries || [];
    var industryButtons = document.getElementById('ivIndustries');
    var progressEl = document.getElementById('ivProgress');
    var labelEl = document.getElementById('ivIndustryLabel');
    var questionEl = document.getElementById('ivQuestion');
    var answerEl = document.getElementById('ivAnswer');
    var toggleBtn = document.getElementById('ivToggle');
    var prevBtn = document.getElementById('ivPrev');
    var nextBtn = document.getElementById('ivNext');
    var industryIndex = 0;
    var questionIndex = 0;
    var answerOpen = false;

    if (!industries.length || !questionEl) {
        if (questionEl) {
            questionEl.textContent = 'Interview questions are not ready yet.';
        }
        return;
    }

    function current() {
        return industries[industryIndex];
    }

    function render() {
        var industry = current();
        var total = industry.questions.length;
        var item = industry.questions[questionIndex];
        labelEl.textContent = industry.title;
        progressEl.textContent = 'Question ' + (questionIndex + 1) + ' of ' + total;
        questionEl.textContent = item.q;
        answerEl.textContent = item.a;
        answerEl.hidden = !answerOpen;
        toggleBtn.textContent = answerOpen ? 'Hide sample answer' : 'Show sample answer';
        prevBtn.hidden = questionIndex === 0;
        nextBtn.textContent = questionIndex >= total - 1 ? 'Start again' : 'Next question';
        Array.prototype.forEach.call(industryButtons.querySelectorAll('.iv-industry'), function (button, i) {
            button.classList.toggle('is-on', i === industryIndex);
        });
    }

    industryButtons.addEventListener('click', function (event) {
        var button = event.target.closest('[data-industry]');
        if (!button) {
            return;
        }
        var id = button.getAttribute('data-industry');
        var found = -1;
        industries.forEach(function (item, i) {
            if (item.id === id) {
                found = i;
            }
        });
        if (found < 0) {
            return;
        }
        industryIndex = found;
        questionIndex = 0;
        answerOpen = false;
        render();
    });

    toggleBtn.addEventListener('click', function () {
        answerOpen = !answerOpen;
        render();
    });

    prevBtn.addEventListener('click', function () {
        if (questionIndex > 0) {
            questionIndex -= 1;
            answerOpen = false;
            render();
        }
    });

    nextBtn.addEventListener('click', function () {
        var total = current().questions.length;
        if (questionIndex >= total - 1) {
            questionIndex = 0;
        } else {
            questionIndex += 1;
        }
        answerOpen = false;
        render();
    });

    render();
})();
