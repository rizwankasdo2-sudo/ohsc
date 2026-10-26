(function () {
    var data = window.OHSC_CQ || { questions: [], paths: {}, asset: '', courses: '/courses' };
    var questions = data.questions || [];
    var paths = data.paths || {};
    var promptEl = document.getElementById('cqPrompt');
    var choicesEl = document.getElementById('cqChoices');
    var progressEl = document.getElementById('cqProgress');
    var nextBtn = document.getElementById('cqNext');
    var needEl = document.getElementById('cqNeed');
    var quizEl = document.getElementById('cqQuiz');
    var resultEl = document.getElementById('cqResult');
    var resultLead = document.getElementById('cqResultLead');
    var resultGrid = document.getElementById('cqResultGrid');
    var againBtn = document.getElementById('cqAgain');
    var index = 0;
    var picked = null;
    var scores = {};
    var choiceIcons = [
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="9" r="2.4"/><circle cx="16" cy="10" r="2.1"/><path d="M4.8 18c.6-2.4 2.2-3.6 4.2-3.6s3.6 1.2 4.2 3.6M13.2 18c.4-1.8 1.6-2.8 3.2-2.8 1.4 0 2.5.8 3 2.4"/></svg>',
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="3"/><path d="M12 2.5v2.2M12 19.3v2.2M2.5 12h2.2M19.3 12h2.2M5.1 5.1l1.6 1.6M17.3 17.3l1.6 1.6M18.9 5.1l-1.6 1.6M6.7 17.3l-1.6 1.6"/></svg>',
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 18h6M10 21h4M12 3a6 6 0 0 1 3.6 10.8c-.7.5-1.1 1.2-1.2 2H9.6c-.1-.8-.5-1.5-1.2-2A6 6 0 0 1 12 3z"/></svg>',
        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19h16M7 15V8l5-4 5 4v7"/><path d="M10 15v-3h4v3"/></svg>'
    ];

    if (!promptEl || !questions.length) {
        if (promptEl) {
            promptEl.textContent = 'The quiz is not ready yet.';
        }
        if (nextBtn) {
            nextBtn.disabled = true;
        }
        return;
    }

    Object.keys(paths).forEach(function (key) {
        scores[key] = 0;
    });

    function resetScores() {
        Object.keys(scores).forEach(function (key) {
            scores[key] = 0;
        });
    }

    function render() {
        var question = questions[index];
        progressEl.textContent = 'Question ' + (index + 1) + ' of ' + questions.length;
        promptEl.textContent = question.prompt;
        choicesEl.innerHTML = '';
        needEl.hidden = true;
        picked = null;
        nextBtn.innerHTML = index === 0
            ? 'Start Free Quiz <span aria-hidden="true">→</span>'
            : (index === questions.length - 1 ? 'See my results' : 'Next question');
        question.choices.forEach(function (choice, choiceIndex) {
            var label = document.createElement('label');
            label.className = 'cq-choice';
            var input = document.createElement('input');
            input.type = 'radio';
            input.name = 'cq-choice';
            input.value = String(choiceIndex);
            input.addEventListener('change', function () {
                picked = choice;
                needEl.hidden = true;
            });
            var icon = document.createElement('i');
            icon.className = 'cq-avatar a' + (choiceIndex % 4);
            icon.setAttribute('aria-hidden', 'true');
            icon.innerHTML = choiceIcons[choiceIndex % choiceIcons.length];
            var text = document.createElement('span');
            text.textContent = choice[0];
            label.appendChild(input);
            label.appendChild(icon);
            label.appendChild(text);
            choicesEl.appendChild(label);
        });
    }

    function applyPick() {
        if (!picked || !picked[1]) {
            return;
        }
        Object.keys(picked[1]).forEach(function (key) {
            if (scores.hasOwnProperty(key)) {
                scores[key] += Number(picked[1][key]) || 0;
            }
        });
    }

    function ranked() {
        return Object.keys(scores).map(function (key) {
            return { key: key, score: scores[key], meta: paths[key] };
        }).filter(function (item) {
            return item.meta;
        }).sort(function (a, b) {
            return b.score - a.score;
        });
    }

    function showResult() {
        var list = ranked().slice(0, 3);
        var top = list[0];
        quizEl.hidden = true;
        resultEl.hidden = false;
        resultLead.textContent = top
            ? 'Your strongest match is ' + top.meta.title + '. Here are the paths that fit your answers best.'
            : 'Explore these career paths based on your answers.';
        resultGrid.innerHTML = '';
        list.forEach(function (item, i) {
            var article = document.createElement('article');
            article.className = 'cq-match';
            var img = document.createElement('img');
            img.src = (data.asset || '') + (item.meta.image || 'career-office.jpg');
            img.alt = '';
            var body = document.createElement('div');
            var rank = document.createElement('p');
            rank.className = 'cq-rank';
            rank.textContent = i === 0 ? 'Best match' : 'Also a good fit';
            var title = document.createElement('h4');
            title.textContent = item.meta.title;
            var summary = document.createElement('p');
            summary.textContent = item.meta.summary;
            var link = document.createElement('a');
            link.href = item.meta.course || data.courses || '/courses';
            link.textContent = 'Explore courses →';
            body.appendChild(rank);
            body.appendChild(title);
            body.appendChild(summary);
            body.appendChild(link);
            article.appendChild(img);
            article.appendChild(body);
            resultGrid.appendChild(article);
        });
        resultEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    nextBtn.addEventListener('click', function () {
        if (index === 0 && picked === null && !choicesEl.querySelector('input:checked')) {
            // first click can start without pick if they haven't selected yet — require pick always
        }
        if (!picked) {
            needEl.hidden = false;
            return;
        }
        applyPick();
        if (index >= questions.length - 1) {
            showResult();
            return;
        }
        index += 1;
        render();
    });

    againBtn.addEventListener('click', function () {
        index = 0;
        picked = null;
        resetScores();
        resultEl.hidden = true;
        quizEl.hidden = false;
        render();
        quizEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    render();
})();
