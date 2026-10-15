(function () {
    var data = window.OHSC_EQ || { questions: [], areas: {} };
    var questions = data.questions || [];
    var areas = data.areas || {};
    var promptEl = document.getElementById('eqPrompt');
    var choicesEl = document.getElementById('eqChoices');
    var progressEl = document.getElementById('eqProgress');
    var nextBtn = document.getElementById('eqNext');
    var needEl = document.getElementById('eqNeed');
    var quizEl = document.getElementById('eqQuiz');
    var scoreEl = document.getElementById('eqScore');
    var barsEl = document.getElementById('eqBars');
    var overallEl = document.getElementById('eqOverall');
    var bandEl = document.getElementById('eqBand');
    var againBtn = document.getElementById('eqAgain');
    var index = 0;
    var picked = null;
    var answers = [];

    if (!promptEl || !questions.length) {
        if (promptEl) {
            promptEl.textContent = 'No questions are published yet. Add them in the admin panel.';
        }
        if (nextBtn) {
            nextBtn.disabled = true;
        }
        return;
    }

    function bandFor(score) {
        if (score < 45) return 'Developing';
        if (score < 75) return 'Growing';
        return 'Strong';
    }

    function render() {
        var question = questions[index];
        progressEl.textContent = 'Question ' + (index + 1) + ' of ' + questions.length;
        promptEl.textContent = question.prompt;
        choicesEl.innerHTML = '';
        needEl.hidden = true;
        picked = null;
        nextBtn.textContent = index === 0 ? 'Start Free Test' : (index === questions.length - 1 ? 'See my result' : 'Next question');
        question.choices.forEach(function (choice, choiceIndex) {
            var label = document.createElement('label');
            label.className = 'ohsc-choice';
            var input = document.createElement('input');
            input.type = 'radio';
            input.name = 'eq-choice';
            input.value = String(choice.id);
            input.addEventListener('change', function () {
                picked = choice;
                needEl.hidden = true;
            });
            var avatar = document.createElement('i');
            avatar.className = 'ohsc-avatar a' + (choiceIndex % 3);
            avatar.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M6.2 18.5c.8-3 2.8-4.4 5.8-4.4s5 1.4 5.8 4.4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
            var text = document.createElement('span');
            text.textContent = choice.text;
            label.appendChild(input);
            label.appendChild(avatar);
            label.appendChild(text);
            choicesEl.appendChild(label);
        });
    }

    function showScore() {
        var totals = {};
        var counts = {};
        Object.keys(areas).forEach(function (key) {
            totals[key] = 0;
            counts[key] = 0;
        });
        answers.forEach(function (answer) {
            if (!totals.hasOwnProperty(answer.area)) {
                return;
            }
            totals[answer.area] += answer.points;
            counts[answer.area] += 1;
        });
        var scores = [];
        var sum = 0;
        var used = 0;
        Object.keys(areas).forEach(function (key) {
            var max = Math.max(1, counts[key] * 3);
            var score = Math.round((totals[key] / max) * 100);
            scores.push({ key: key, name: areas[key][0], score: score, band: bandFor(score) });
            if (counts[key] > 0) {
                sum += score;
                used += 1;
            }
        });
        var overall = used ? Math.round(sum / used) : 0;
        overallEl.textContent = String(overall);
        bandEl.textContent = bandFor(overall) + '. Your answers show how you notice feelings, steady yourself, and respond to other people.';
        barsEl.innerHTML = '';
        scores.forEach(function (item) {
            var row = document.createElement('div');
            row.className = 'ohsc-bar';
            row.innerHTML = '<div><strong></strong><span></span></div><b></b><i></i>';
            row.querySelector('strong').textContent = item.name;
            row.querySelector('span').textContent = item.band;
            row.querySelector('b').textContent = item.score;
            row.querySelector('i').style.width = item.score + '%';
            barsEl.appendChild(row);
        });
        quizEl.hidden = true;
        scoreEl.hidden = false;
        scoreEl.scrollIntoView({ block: 'center' });
    }

    nextBtn.addEventListener('click', function () {
        if (!picked) {
            needEl.hidden = false;
            return;
        }
        answers[index] = {
            area: questions[index].area,
            points: picked.points
        };
        if (index >= questions.length - 1) {
            showScore();
            return;
        }
        index += 1;
        render();
    });

    againBtn.addEventListener('click', function () {
        index = 0;
        answers = [];
        scoreEl.hidden = true;
        quizEl.hidden = false;
        render();
        quizEl.scrollIntoView({ block: 'center' });
    });

    render();
})();
