(function () {
    var root = document.getElementById('ohsc-typing');
    if (!root || !window.OHSC_TYPING) {
        return;
    }

    var passages = window.OHSC_TYPING.passages || [];
    var passageEl = document.getElementById('passage');
    var inputEl = document.getElementById('capture');
    var startBtn = document.getElementById('startTest');
    var restartBtn = document.getElementById('restartTest');
    var newTextBtn = document.getElementById('newText');
    var symbolsBtn = document.getElementById('modeSymbols');
    var numbersBtn = document.getElementById('modeNumbers');
    var scoreEl = document.getElementById('scoreBox');
    var timeButtons = root.querySelectorAll('[data-seconds]');
    var statTime = document.getElementById('statTime');
    var statWords = document.getElementById('statWords');
    var statWpm = document.getElementById('statWpm');
    var statAccuracy = document.getElementById('statAccuracy');
    var statErrors = document.getElementById('statErrors');
    var resultWpm = document.getElementById('resultWpm');
    var resultAccuracy = document.getElementById('resultAccuracy');
    var resultErrors = document.getElementById('resultErrors');
    var resultTime = document.getElementById('resultTime');

    var duration = 60;
    var target = '';
    var typedChars = [];
    var typedOk = [];
    var correct = 0;
    var errors = 0;
    var started = false;
    var finished = false;
    var startedAt = 0;
    var timer = null;
    var useSymbols = false;
    var useNumbers = false;
    var lastKey = '';

    function shuffle(list) {
        var copy = list.slice();
        for (var i = copy.length - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var hold = copy[i];
            copy[i] = copy[j];
            copy[j] = hold;
        }
        return copy;
    }

    function plainWords(text) {
        return text
            .replace(/[^A-Za-z\s]/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function buildParagraph() {
        var bank = shuffle(passages.filter(function (text) { return text.trim().length > 0; }));
        if (!bank.length) return '';
        var text = bank[0].trim().replace(/\s+/g, ' ');
        if (useNumbers) text += ' Reference 123: complete 45 tasks in 60 minutes.';
        if (useSymbols) text += ' Check @, #, %, & and ! carefully.';
        return text;
    }

    function clock(totalSeconds) {
        var safe = Math.max(0, totalSeconds);
        var mins = Math.floor(safe / 60);
        var secs = safe % 60;
        return mins + ':' + (secs < 10 ? '0' : '') + secs;
    }

    function wordsIn(text) {
        var parts = text.trim().split(/\s+/);
        return text.trim() ? parts.length : 0;
    }

    function wpmNow() {
        if (!startedAt) {
            return 0;
        }
        var elapsed = Date.now() - startedAt;
        if (elapsed < 1000) {
            return 0;
        }
        return Math.round((correct / 5) / (elapsed / 60000));
    }

    function accuracyNow() {
        var typedKeys = correct + errors;
        if (!typedKeys) {
            return 100;
        }
        return Math.max(0, Math.min(100, Math.round((correct / typedKeys) * 100)));
    }

    function levelFor(wpm) {
        if (wpm < 26) return 'Hunt and peck';
        if (wpm < 41) return 'Novice';
        if (wpm < 56) return 'Beginner';
        if (wpm < 71) return 'Intermediate';
        if (wpm < 91) return 'Proficient';
        if (wpm < 121) return 'Advanced';
        return 'Expert';
    }

    function renderPassage() {
        passageEl.querySelectorAll('span').forEach(function (node) {
            node.parentNode.removeChild(node);
        });
        if (!target) {
            var empty = document.createElement('span');
            empty.textContent = 'No typing text is published yet. Add one in the admin panel.';
            passageEl.appendChild(empty);
            return;
        }
        var startAt = 0;
        var endAt = target.length;
        var frag = document.createDocumentFragment();
        for (var i = startAt; i < endAt; i++) {
            var span = document.createElement('span');
            var ch = target.charAt(i);
            span.textContent = ch === ' ' ? ' ' : ch;
            if (i < typedChars.length && typedOk[i]) {
                span.className = 'is-done';
            } else if (i < typedChars.length) {
                span.className = 'is-bad' + (ch === ' ' ? ' is-gap' : '');
            } else if (i === typedChars.length) {
                span.className = 'is-now';
            }
            frag.appendChild(span);
        }
        passageEl.appendChild(frag);
        var now = passageEl.querySelector('.is-now');
        if (now) {
            var top = now.offsetTop - passageEl.offsetTop;
            if (top > 90) {
                passageEl.scrollTop = top - 70;
            }
        }
    }

    function paintLiveStats() {
        if (!started) {
            statTime.textContent = clock(duration);
            statWords.textContent = '–';
            statWpm.textContent = '–';
            statAccuracy.textContent = '–';
            statErrors.textContent = '–';
            return;
        }
        var left = Math.max(0, duration - Math.floor((Date.now() - startedAt) / 1000));
        statTime.textContent = clock(left);
        statWords.textContent = String(wordsIn(typedChars.join('')));
        statWpm.textContent = String(wpmNow());
        statAccuracy.textContent = accuracyNow() + '%';
        statErrors.textContent = String(errors);
    }

    function resetResults() {
        resultWpm.textContent = '–';
        resultAccuracy.textContent = '–';
        resultErrors.textContent = '–';
        resultTime.textContent = '–';
        scoreEl.className = 'ohsc-score';
        scoreEl.innerHTML = '';
    }

    function finish() {
        if (finished) {
            return;
        }
        finished = true;
        started = false;
        clearInterval(timer);
        startBtn.disabled = false;
        startBtn.textContent = 'Start Free Test';
        timeButtons.forEach(function (button) { button.disabled = false; });
        var wpm = wpmNow();
        var accuracy = accuracyNow();
        var taken = Math.min(duration, Math.round((Date.now() - startedAt) / 1000));
        statTime.textContent = clock(Math.max(0,duration-taken));
        statWords.textContent = String(wordsIn(typedChars.join('')));
        statWpm.textContent = String(wpm);
        statAccuracy.textContent = accuracy + '%';
        statErrors.textContent = String(errors);
        resultWpm.textContent = wpm + ' WPM';
        resultAccuracy.textContent = accuracy + '%';
        resultErrors.textContent = String(errors);
        resultTime.textContent = clock(taken);
        scoreEl.className = 'ohsc-score is-on';
        scoreEl.innerHTML = '<strong>' + wpm + ' WPM</strong><p>' + levelFor(wpm) + ' · ' + accuracy + '% accuracy · ' + errors + ' errors</p>';
    }

    function tick() {
        var elapsed = Date.now() - startedAt;
        if (elapsed >= duration * 1000) {
            finish();
            return;
        }
        paintLiveStats();
    }

    function begin() {
        if (!target || started) {
            return;
        }
        finished = false;
        started = true;
        startedAt = Date.now();
        inputEl.focus();
        startBtn.disabled = true;
        timeButtons.forEach(function (button) { button.disabled = true; });
        paintLiveStats();
        clearInterval(timer);
        timer = setInterval(tick, 200);
    }

    function resetProgress() {
        clearInterval(timer);
        finished = false;
        started = false;
        startedAt = 0;
        typedChars = [];
        typedOk = [];
        correct = 0;
        errors = 0;
        inputEl.value = '';
        startBtn.disabled = !target;
        timeButtons.forEach(function (button) { button.disabled = false; });
        resetResults();
        renderPassage();
        paintLiveStats();
    }

    function loadText(forceNew) {
        var next = buildParagraph();
        var tries = 0;
        while (forceNew && next === target && tries < 6) {
            next = buildParagraph();
            tries++;
        }
        target = next;
        lastKey = target.slice(0, 80);
        resetProgress();
    }

    function extendTarget() {
        if (typedChars.length < target.length - 40) {
            return;
        }
        target += ' ' + buildParagraph();
    }

    timeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (started) {
                return;
            }
            duration = parseInt(button.getAttribute('data-seconds'), 10);
            timeButtons.forEach(function (other) {
                other.classList.toggle('is-on', other === button);
            });
            paintLiveStats();
        });
    });

    function bindMode(button, flagName) {
        button.addEventListener('click', function () {
            if (flagName === 'symbols') {
                useSymbols = !useSymbols;
                button.classList.toggle('is-on', useSymbols);
                button.setAttribute('aria-pressed', useSymbols ? 'true' : 'false');
            } else {
                useNumbers = !useNumbers;
                button.classList.toggle('is-on', useNumbers);
                button.setAttribute('aria-pressed', useNumbers ? 'true' : 'false');
            }
            loadText(true);
            inputEl.focus();
        });
    }
    bindMode(symbolsBtn, 'symbols');
    bindMode(numbersBtn, 'numbers');

    startBtn.addEventListener('click', function () {
        if (finished || typedChars.length) {
            resetProgress();
        }
        begin();
    });

    restartBtn.addEventListener('click', function () {
        resetProgress();
        inputEl.focus();
    });

    newTextBtn.addEventListener('click', function () {
        var previous = lastKey;
        loadText(true);
        if (target.slice(0, 80) === previous) {
            loadText(true);
        }
        inputEl.focus();
    });

    passageEl.addEventListener('click', function () {
        inputEl.focus();
    });

    inputEl.addEventListener('paste', function (event) { event.preventDefault(); });
    inputEl.addEventListener('drop', function (event) { event.preventDefault(); });
    inputEl.addEventListener('input', function () {
        if (finished || !target) { inputEl.value=typedChars.join(''); return; }
        if (!started) begin();
        var value=inputEl.value.replace(/\r?\n/g,' ');
        if (value.length>target.length) value=value.slice(0,target.length);
        inputEl.value=value;
        var previous=typedChars.slice();
        typedChars=Array.from(value);typedOk=[];correct=0;
        typedChars.forEach(function(ch,i) {
            var ok=ch===target.charAt(i);typedOk.push(ok);
            if (ok) correct++;else if (previous[i]!==ch) errors++;
        });
        renderPassage();paintLiveStats();
        if (typedChars.length===target.length) finish();
    });

    loadText(false);
})();
