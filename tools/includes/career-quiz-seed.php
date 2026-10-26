<?php
/**
 * Seed data for the free Career Quiz.
 * Each choice adds points to one or more career paths.
 */
return array(
    'paths' => array(
        'healthcare' => array(
            'title' => 'Healthcare & Care',
            'summary' => 'You are drawn to helping people, listening carefully, and making a real difference day to day.',
            'image' => 'career-health-2.jpg',
            'course' => '/courses',
        ),
        'business' => array(
            'title' => 'Business & Administration',
            'summary' => 'You like organising work, solving practical problems, and keeping teams and plans on track.',
            'image' => 'career-office.jpg',
            'course' => '/courses',
        ),
        'technology' => array(
            'title' => 'Technology & Digital',
            'summary' => 'You enjoy systems, problem-solving, and building or improving how things work online.',
            'image' => 'career-data.jpg',
            'course' => '/courses',
        ),
        'education' => array(
            'title' => 'Education & Training',
            'summary' => 'You like explaining ideas, supporting learners, and helping other people grow.',
            'image' => 'eq-course-talk.jpg',
            'course' => '/courses',
        ),
        'creative' => array(
            'title' => 'Creative & Marketing',
            'summary' => 'You thrive when you can invent ideas, communicate clearly, and shape how things look and feel.',
            'image' => 'career-service.jpg',
            'course' => '/courses',
        ),
    ),
    'questions' => array(
        array(
            'prompt' => 'Which kind of work energises you most?',
            'choices' => array(
                array('Helping people and making a difference', array('healthcare' => 3, 'education' => 1)),
                array('Solving problems and finding solutions', array('technology' => 3, 'business' => 1)),
                array('Creating new ideas and being creative', array('creative' => 3, 'technology' => 1)),
            ),
        ),
        array(
            'prompt' => 'In a group project, which role feels most natural?',
            'choices' => array(
                array('Supporting teammates and keeping everyone included', array('healthcare' => 2, 'education' => 2)),
                array('Planning the steps and checking progress', array('business' => 3, 'education' => 1)),
                array('Building the technical or digital parts', array('technology' => 3)),
                array('Designing the look, message, or presentation', array('creative' => 3)),
            ),
        ),
        array(
            'prompt' => 'Which school subject did you enjoy most?',
            'choices' => array(
                array('Biology, health, or caring subjects', array('healthcare' => 3)),
                array('Business, maths, or economics', array('business' => 3)),
                array('Computing, science, or coding', array('technology' => 3)),
                array('English, art, media, or languages', array('creative' => 2, 'education' => 2)),
            ),
        ),
        array(
            'prompt' => 'What kind of workplace suits you best?',
            'choices' => array(
                array('A calm place where I support people one to one', array('healthcare' => 3, 'education' => 1)),
                array('A busy office with clear goals and deadlines', array('business' => 3)),
                array('A flexible space with screens, tools, and systems', array('technology' => 3)),
                array('A studio or team where ideas are shared openly', array('creative' => 3)),
            ),
        ),
        array(
            'prompt' => 'When a problem appears, what do you do first?',
            'choices' => array(
                array('Listen carefully to how people feel', array('healthcare' => 2, 'education' => 2)),
                array('Break it into steps and assign actions', array('business' => 3)),
                array('Test what is broken and fix the process', array('technology' => 3)),
                array('Sketch a new approach or message', array('creative' => 3)),
            ),
        ),
        array(
            'prompt' => 'Which free-time activity sounds most like you?',
            'choices' => array(
                array('Volunteering or looking after someone', array('healthcare' => 3)),
                array('Organising an event or small project', array('business' => 2, 'education' => 1)),
                array('Learning a new app, tool, or game system', array('technology' => 3)),
                array('Writing, designing, filming, or making things', array('creative' => 3)),
            ),
        ),
        array(
            'prompt' => 'What feedback do people often give you?',
            'choices' => array(
                array('You are patient and easy to talk to', array('healthcare' => 2, 'education' => 2)),
                array('You are reliable and well organised', array('business' => 3)),
                array('You are logical and quick with details', array('technology' => 3)),
                array('You are imaginative and persuasive', array('creative' => 3)),
            ),
        ),
        array(
            'prompt' => 'Which career goal feels closest to you?',
            'choices' => array(
                array('Improve health, safety, or wellbeing', array('healthcare' => 3)),
                array('Run projects, teams, or a business', array('business' => 3)),
                array('Build digital products or secure systems', array('technology' => 3)),
                array('Teach, coach, or help others learn', array('education' => 3)),
            ),
        ),
        array(
            'prompt' => 'How do you prefer to spend a working day?',
            'choices' => array(
                array('Meeting people and responding to needs', array('healthcare' => 2, 'education' => 2)),
                array('Emails, plans, budgets, and meetings', array('business' => 3)),
                array('Deep focus on a technical task', array('technology' => 3)),
                array('Brainstorming and shaping content or design', array('creative' => 3)),
            ),
        ),
        array(
            'prompt' => 'What would make a job feel worthwhile?',
            'choices' => array(
                array('Knowing I helped someone feel better', array('healthcare' => 3, 'education' => 1)),
                array('Seeing a plan finish on time and on budget', array('business' => 3)),
                array('Shipping something that works every day', array('technology' => 3)),
                array('Making an idea clear and memorable', array('creative' => 3)),
            ),
        ),
    ),
);
