(function () {
    var form = document.getElementById('cvForm');
    if (!form) {
        return;
    }

    var steps = ['Personal details', 'Experience', 'Education', 'Skills', 'Review'];
    var sample = {
        personal: {
            name: 'James Anderson',
            title: 'Marketing Manager',
            email: 'james.anderson@email.com',
            phone: '+44 7123 456789',
            location: 'London, UK',
            summary: 'Marketing manager who plans campaigns, writes clearly, and helps teams launch work on time.'
        },
        experience: [{
            role: 'Marketing Manager',
            company: 'Northline Studio',
            start: '2021',
            end: 'Present',
            detail: 'Led campaign planning and wrote briefs the team could follow.'
        }],
        education: [{
            qualification: 'BA Marketing',
            school: 'City College',
            start: '2016',
            end: '2019'
        }],
        skills: ['Campaign planning', 'Copywriting', 'Team leadership']
    };
    var emptyJob = function () { return { role: '', company: '', start: '', end: '', detail: '' }; };
    var emptySchool = function () { return { qualification: '', school: '', start: '', end: '' }; };
    var state = load();

    var fields = document.getElementById('cvFields');
    var error = document.getElementById('cvError');
    var live = document.getElementById('cvLive');
    var note = document.getElementById('cvSampleNote');
    var modal = document.getElementById('cvModal');
    var modalSheet = document.getElementById('cvModalSheet');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        readStep();
        if (!valid()) {
            return;
        }
        if (state.step < steps.length) {
            state.step += 1;
            save();
            paint();
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            openPreview();
        }
    });
    document.getElementById('cvBack').addEventListener('click', function () {
        readStep();
        if (state.step > 1) {
            state.step -= 1;
            save();
            paint();
        }
    });
    document.getElementById('cvTemplates').addEventListener('click', function (event) {
        var button = event.target.closest('[data-template]');
        if (!button) {
            return;
        }
        state.template = button.getAttribute('data-template');
        save();
        paint();
    });
    document.getElementById('cvOpenPreview').addEventListener('click', openPreview);
    document.getElementById('cvClosePreview').addEventListener('click', function () {
        modal.hidden = true;
    });
    document.getElementById('cvDownload').addEventListener('click', download);
    fields.addEventListener('input', function () {
        readStep();
        if (state.step === 1) {
            var count = document.getElementById('cvCount');
            if (count) {
                count.textContent = state.personal.summary.length + '/300';
            }
        }
        renderPreview();
        save();
    });
    fields.addEventListener('click', function (event) {
        var add = event.target.closest('[data-add]');
        var remove = event.target.closest('[data-remove]');
        var skill = event.target.closest('[data-skill]');
        readStep();
        if (add === 'job' || (add && add.getAttribute('data-add') === 'job')) {
            state.experience.push(emptyJob());
        } else if (add && add.getAttribute('data-add') === 'school') {
            state.education.push(emptySchool());
        } else if (add && add.getAttribute('data-add') === 'skill') {
            pushSkill();
        } else if (remove) {
            var list = remove.getAttribute('data-remove');
            var index = Number(remove.getAttribute('data-index'));
            if (list === 'job' && state.experience.length > 1) {
                state.experience.splice(index, 1);
            }
            if (list === 'school' && state.education.length > 1) {
                state.education.splice(index, 1);
            }
        } else if (skill) {
            state.skills.splice(Number(skill.getAttribute('data-skill')), 1);
        } else {
            return;
        }
        save();
        paint();
    });
    fields.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && event.target.id === 'cvSkillInput') {
            event.preventDefault();
            readStep();
            pushSkill();
            save();
            paint();
        }
    });

    paint();
    setTimeout(function () {
        loadHtml2Pdf().catch(function () {});
    }, 800);

    function paint() {
        document.getElementById('cvStepNum').textContent = String(state.step);
        document.getElementById('cvStepCount').textContent = state.step + ' of ' + steps.length;
        document.getElementById('cvStepTitle').textContent = steps[state.step - 1];
        document.getElementById('cvBar').style.width = ((state.step / steps.length) * 100) + '%';
        document.getElementById('cvBack').hidden = state.step === 1;
        document.getElementById('cvNext').textContent = state.step === steps.length ? 'Preview & download' : 'Save & Continue';
        error.hidden = true;
        fields.innerHTML = renderStep();
        var buttons = document.querySelectorAll('#cvTemplates button');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].classList.toggle('is-on', buttons[i].getAttribute('data-template') === state.template);
        }
        renderPreview();
    }

    function renderStep() {
        if (state.step === 1) {
            var person = state.personal;
            return '<div class="cv-fields">' +
                field('Full name', 'name', person.name, 'Enter your full name') +
                field('Job title', 'title', person.title, 'Enter your job title') +
                '<div class="cv-row">' + field('Email address', 'email', person.email, 'Enter your email address') + field('Phone number', 'phone', person.phone, 'Enter your phone number') + '</div>' +
                field('Location', 'location', person.location, 'Enter your location') +
                '<label>Professional summary<textarea id="cvSummary" maxlength="300" placeholder="Write 2–3 lines about your professional background, key skills and career goals.">' + esc(person.summary) + '</textarea></label>' +
                '<p class="cv-count" id="cvCount">' + person.summary.length + '/300</p></div>';
        }
        if (state.step === 2) {
            return state.experience.map(function (job, index) {
                return '<div class="cv-block"><div class="cv-block-top"><strong>Role ' + (index + 1) + '</strong><button type="button" class="cv-remove" data-remove="job" data-index="' + index + '">Remove</button></div>' +
                    '<div class="cv-row">' + field('Job title', 'role-' + index, job.role, 'Marketing manager') + field('Company', 'company-' + index, job.company, 'Company name') + '</div>' +
                    '<div class="cv-row">' + field('Start', 'start-' + index, job.start, '2021') + field('End', 'end-' + index, job.end, 'Present') + '</div>' +
                    '<label>What you did<textarea data-key="detail-' + index + '" placeholder="One or two lines about the work.">' + esc(job.detail) + '</textarea></label></div>';
            }).join('') + '<button type="button" class="cv-add" data-add="job">Add another role</button>';
        }
        if (state.step === 3) {
            return state.education.map(function (item, index) {
                return '<div class="cv-block"><div class="cv-block-top"><strong>Study ' + (index + 1) + '</strong><button type="button" class="cv-remove" data-remove="school" data-index="' + index + '">Remove</button></div>' +
                    field('Qualification', 'qualification-' + index, item.qualification, 'BA Marketing') +
                    field('School or college', 'school-' + index, item.school, 'City College') +
                    '<div class="cv-row">' + field('Start', 'edu-start-' + index, item.start, '2016') + field('End', 'edu-end-' + index, item.end, '2019') + '</div></div>';
            }).join('') + '<button type="button" class="cv-add" data-add="school">Add another qualification</button>';
        }
        if (state.step === 4) {
            var chips = state.skills.map(function (skill, index) {
                return '<span class="cv-skill">' + esc(skill) + '<button type="button" data-skill="' + index + '" aria-label="Remove skill">×</button></span>';
            }).join('');
            return '<div class="cv-skills">' + chips + '</div><div class="cv-fields"><label>Add a skill<input id="cvSkillInput" placeholder="Type a skill and press Add"></label></div><button type="button" class="cv-add" data-add="skill">Add skill</button>';
        }
        var person = state.personal;
        return '<div class="cv-review"><div><strong>' + esc(person.name || 'Name not added yet') + '</strong><span>' + esc(person.title) + '</span></div>' +
            '<div>' + filled(state.experience, 'role') + ' roles · ' + filled(state.education, 'qualification') + ' qualifications · ' + state.skills.length + ' skills</div>' +
            '<div>Template: ' + esc(labelFor(state.template)) + '. You can change it below the preview.</div></div>';
    }

    function field(label, key, value, placeholder) {
        return '<label>' + label + '<input data-key="' + key + '" value="' + esc(value) + '" placeholder="' + esc(placeholder) + '"></label>';
    }

    function readStep() {
        if (state.step === 1) {
            state.personal.name = valueOf('name');
            state.personal.title = valueOf('title');
            state.personal.email = valueOf('email');
            state.personal.phone = valueOf('phone');
            state.personal.location = valueOf('location');
            var summary = document.getElementById('cvSummary');
            state.personal.summary = summary ? summary.value.slice(0, 300) : '';
            return;
        }
        if (state.step === 2) {
            state.experience.forEach(function (job, index) {
                job.role = valueOf('role-' + index);
                job.company = valueOf('company-' + index);
                job.start = valueOf('start-' + index);
                job.end = valueOf('end-' + index);
                job.detail = valueOf('detail-' + index);
            });
        }
        if (state.step === 3) {
            state.education.forEach(function (item, index) {
                item.qualification = valueOf('qualification-' + index);
                item.school = valueOf('school-' + index);
                item.start = valueOf('edu-start-' + index);
                item.end = valueOf('edu-end-' + index);
            });
        }
    }

    function valueOf(key) {
        var node = fields.querySelector('[data-key="' + key + '"]');
        return node ? node.value.trim() : '';
    }

    function valid() {
        if (state.step === 1 && state.personal.name === '') {
            error.hidden = false;
            error.textContent = 'Add your full name to continue.';
            return false;
        }
        return true;
    }

    function pushSkill() {
        var input = document.getElementById('cvSkillInput');
        var skill = input ? input.value.trim() : '';
        if (skill && state.skills.length < 16 && state.skills.indexOf(skill) === -1) {
            state.skills.push(skill);
        }
    }

    function usingSample() {
        var person = state.personal;
        if (person.name || person.title || person.email || person.phone || person.location || person.summary) {
            return false;
        }
        if (state.skills.length) {
            return false;
        }
        if (state.experience.some(function (job) { return job.role || job.company || job.detail; })) {
            return false;
        }
        return !state.education.some(function (item) { return item.qualification || item.school; });
    }

    function currentData() {
        if (usingSample()) {
            return sample;
        }
        return {
            personal: state.personal,
            experience: state.experience.filter(function (job) { return job.role || job.company || job.detail; }),
            education: state.education.filter(function (item) { return item.qualification || item.school; }),
            skills: state.skills.slice()
        };
    }

    function renderPreview() {
        var data = currentData();
        var html = sheet(data, state.template);
        live.innerHTML = html;
        if (!modal.hidden) {
            modalSheet.innerHTML = html;
        }
        note.hidden = !usingSample();
    }

    function openPreview() {
        readStep();
        save();
        modal.hidden = false;
        modalSheet.innerHTML = sheet(currentData(), state.template);
        loadHtml2Pdf().catch(function () {});
    }

    function download() {
        var button = document.getElementById('cvDownload');
        if (button.disabled) {
            return;
        }
        readStep();
        save();
        var data = currentData();
        var rawName = (data.personal.name || 'CV').replace(/[^\w\s-]/g, '').trim() || 'CV';
        var filename = rawName.replace(/\s+/g, '-') + '-CV.pdf';
        modal.hidden = false;
        modalSheet.innerHTML = sheet(data, state.template);
        var el = modalSheet.querySelector('.cv-sheet');
        if (!el) {
            error.hidden = false;
            error.textContent = 'Could not create the PDF. Please try again.';
            return;
        }

        var prev = {
            width: el.style.width,
            maxWidth: el.style.maxWidth,
            border: el.style.border,
            borderRadius: el.style.borderRadius,
            minHeight: el.style.minHeight,
            boxShadow: el.style.boxShadow
        };
        el.style.width = '794px';
        el.style.maxWidth = '794px';
        el.style.border = '0';
        el.style.borderRadius = '0';
        el.style.minHeight = '0';
        el.style.boxShadow = 'none';

        button.disabled = true;
        button.textContent = 'Preparing PDF…';

        waitForPaint().then(function () {
            return loadHtml2Pdf();
        }).then(function () {
            return window.html2pdf().set({
                margin: [8, 8, 8, 8],
                filename: filename,
                image: { type: 'jpeg', quality: 0.92 },
                html2canvas: {
                    scale: 1.5,
                    useCORS: true,
                    logging: false,
                    backgroundColor: '#ffffff',
                    scrollX: 0,
                    scrollY: 0
                },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            }).from(el).save();
        }).catch(function () {
            error.hidden = false;
            error.textContent = 'Could not create the PDF. Please try again.';
        }).then(function () {
            el.style.width = prev.width;
            el.style.maxWidth = prev.maxWidth;
            el.style.border = prev.border;
            el.style.borderRadius = prev.borderRadius;
            el.style.minHeight = prev.minHeight;
            el.style.boxShadow = prev.boxShadow;
            button.disabled = false;
            button.textContent = 'Download PDF';
        });
    }

    function waitForPaint() {
        return new Promise(function (resolve) {
            requestAnimationFrame(function () {
                requestAnimationFrame(resolve);
            });
        });
    }

    function loadHtml2Pdf() {
        if (window.html2pdf) {
            return Promise.resolve();
        }
        if (loadHtml2Pdf.pending) {
            return loadHtml2Pdf.pending;
        }
        var src = (window.OHSC_CV && window.OHSC_CV.html2pdf) ? window.OHSC_CV.html2pdf : 'https://cdn.jsdelivr.net/npm/html2pdf.js@0.10.1/dist/html2pdf.bundle.min.js';
        loadHtml2Pdf.pending = new Promise(function (resolve, reject) {
            var script = document.createElement('script');
            script.src = src;
            script.onload = function () {
                if (window.html2pdf) {
                    resolve();
                } else {
                    reject(new Error('html2pdf missing'));
                }
            };
            script.onerror = function () {
                loadHtml2Pdf.pending = null;
                reject(new Error('html2pdf failed'));
            };
            document.head.appendChild(script);
        });
        return loadHtml2Pdf.pending;
    }

    function sheet(data, template) {
        var person = data.personal;
        var name = person.name || 'Your name';
        var contactBits = [person.email, person.phone, person.location].filter(Boolean);
        var contactLine = contactBits.join(' · ');
        var contactBlock = contactBits.length
            ? contactBits.map(function (bit) { return '<span>' + esc(bit) + '</span>'; }).join('')
            : '';
        var summary = person.summary ? '<p>' + esc(person.summary) + '</p>' : '';
        var jobs = data.experience.length ? data.experience.map(function (job) {
            return '<div class="job"><div class="role">' + esc(job.role || 'Role') + (job.company ? ' · ' + esc(job.company) : '') + '</div><div class="when">' + esc([job.start, job.end].filter(Boolean).join(' – ')) + '</div><p>' + esc(job.detail) + '</p></div>';
        }).join('') : '<p class="cv-empty">Experience will appear here.</p>';
        var schools = data.education.length ? data.education.map(function (item) {
            return '<div class="school"><div class="role">' + esc(item.qualification || 'Qualification') + (item.school ? ' · ' + esc(item.school) : '') + '</div><div class="when">' + esc([item.start, item.end].filter(Boolean).join(' – ')) + '</div></div>';
        }).join('') : '<p class="cv-empty">Education will appear here.</p>';
        var skills = data.skills.length ? data.skills.map(function (skill) { return '<li>' + esc(skill) + '</li>'; }).join('') : '<li>Skills will appear here.</li>';
        var sideContact = contactBits.length
            ? contactBits.map(function (bit) { return '<p>' + esc(bit) + '</p>'; }).join('')
            : '<p class="cv-empty">Contact will appear here.</p>';
        if (template === 'classic') {
            return '<article class="cv-sheet cv-classic"><header><h3>' + esc(name) + '</h3><p>' + esc(person.title) + '</p><p class="contact contact-stack">' + (contactBlock || esc(contactLine) || 'Contact will appear here.') + '</p></header><h4>Profile</h4>' + (summary || '<p class="cv-empty">Your summary will appear here.</p>') + '<h4>Experience</h4>' + jobs + '<h4>Education</h4>' + schools + '<h4>Skills</h4><p>' + esc(data.skills.join(', ') || 'Skills will appear here.') + '</p></article>';
        }
        if (template === 'minimal') {
            return '<article class="cv-sheet cv-minimal"><h3>' + esc(name) + '</h3><p class="jobtitle">' + esc(person.title) + '</p><p class="contact contact-stack">' + (contactBlock || esc(contactLine) || 'Contact will appear here.') + '</p><h4>Profile</h4>' + (summary || '<p class="cv-empty">Your summary will appear here.</p>') + '<h4>Experience</h4>' + jobs + '<h4>Education</h4>' + schools + '<h4>Skills</h4><p>' + esc(data.skills.join('  ·  ') || 'Skills will appear here.') + '</p></article>';
        }
        return '<article class="cv-sheet cv-modern"><aside class="side"><div class="avatar">' + esc(initials(name)) + '</div><h3>' + esc(name) + '</h3><p>' + esc(person.title) + '</p><h4>Contact</h4>' + sideContact + '<h4>Skills</h4><ul>' + skills + '</ul></aside><div class="main"><h4>Professional summary</h4>' + (summary || '<p class="cv-empty">Your summary will appear here.</p>') + '<h4>Experience</h4>' + jobs + '<h4>Education</h4>' + schools + '</div></article>';
    }

    function initials(name) {
        var parts = name.split(' ').filter(Boolean).slice(0, 2);
        return parts.map(function (part) { return part.charAt(0).toUpperCase(); }).join('') || 'CV';
    }

    function filled(list, key) {
        return list.filter(function (item) { return item[key]; }).length;
    }

    function labelFor(template) {
        if (template === 'classic') { return 'Classic'; }
        if (template === 'minimal') { return 'Minimal'; }
        return 'Modern';
    }

    function esc(value) {
        return String(value || '').replace(/[&<>"']/g, function (char) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[char];
        });
    }

    function load() {
        var fresh = {
            step: 1,
            template: 'modern',
            personal: { name: '', title: '', email: '', phone: '', location: '', summary: '' },
            experience: [emptyJob()],
            education: [emptySchool()],
            skills: []
        };
        try {
            var saved = JSON.parse(localStorage.getItem('ohscCvBuilder') || 'null');
            if (!saved || !saved.personal) {
                return fresh;
            }
            saved.step = Math.min(5, Math.max(1, Number(saved.step) || 1));
            if (['modern', 'classic', 'minimal'].indexOf(saved.template) === -1) {
                saved.template = 'modern';
            }
            if (!saved.experience || !saved.experience.length) {
                saved.experience = [emptyJob()];
            }
            if (!saved.education || !saved.education.length) {
                saved.education = [emptySchool()];
            }
            if (!saved.skills) {
                saved.skills = [];
            }
            saved.personal.summary = saved.personal.summary || '';
            saved.personal.name = saved.personal.name || '';
            saved.personal.title = saved.personal.title || '';
            saved.personal.email = saved.personal.email || '';
            saved.personal.phone = saved.personal.phone || '';
            saved.personal.location = saved.personal.location || '';
            return saved;
        } catch (error) {
            return fresh;
        }
    }

    function save() {
        try {
            localStorage.setItem('ohscCvBuilder', JSON.stringify(state));
        } catch (error) {}
    }
})();
