from pathlib import Path
import json, shutil, zipfile
from xml.sax.saxutils import escape
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak
from reportlab.lib import colors
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.enums import TA_LEFT
from reportlab.lib.pagesizes import A4
ROOT=Path(__file__).resolve().parents[2]
OUT=ROOT/'output/pdf'; DEST=ROOT/'tools/seed-resources'
OUT.mkdir(parents=True,exist_ok=True); DEST.mkdir(parents=True,exist_ok=True)
records=[]
def add(key, title, category, sub, goal, intro, sections, answer=''):
    records.append(dict(seed_key=key,title=title,category=category,subcategory=sub,goal=goal,summary=intro,sections=sections,answer=answer))
add('professional-cv','Professional CV Template','templates','cv-templates','find-job','Build a clear CV with prompts for your profile, experience, education and skills.',[
('Your details',['Full name: __________________________________________','Job title or target role: _________________________________','Email | Phone | Location | Portfolio: _______________________']),
('Professional profile',['Write 2-3 lines about your experience, strengths and target role.','Example structure: [Role] with [experience] in [area]. Skilled in [skills]. Seeking to contribute [value] to [type of team].']),
('Experience',['Job title | Organisation | Dates','Achievement: [Action taken] + [task or challenge] + [result].','Add 2-4 relevant achievements. Use honest evidence and numbers where available.']),
('Education and skills',['Qualification | Institution | Dates','List relevant technical skills, transferable skills and completed training.','Before sending: tailor the content, check dates and proofread contact details.'])])
add('cover-letter','General Cover Letter Template','templates','cover-letter-templates','find-job','A practical structure for a tailored one-page cover letter.',[
('Contact and opening',['[Your name] | [Email] | [Phone] | [Date]','Dear [Hiring manager name / Hiring team],','I am applying for [role] at [organisation]. I am interested because [specific reason].']),
('Show your fit',['In [role/project], I [relevant action] which resulted in [honest outcome].','This helped me develop [skill] that relates to your requirement for [requirement].']),
('Close with a next step',['I would welcome the opportunity to discuss how I can contribute to [team goal].','Thank you for considering my application.','Yours sincerely, [Name]']),
('Final checks',['Name the right organisation and role. Include evidence rather than repeating the CV.','Remove all placeholders. Keep the letter focused and easy to read.'])])
add('career-plan','Career Plan Template','templates','career-planning-templates','plan-career','Turn a career goal into specific steps and review dates.',[
('Starting point',['Current role or situation: _________________________________','Interests and values: _____________________________________','Strengths with examples: __________________________________']),
('Direction',['Career option to explore: _________________________________','What attracts me to it? ___________________________________','What information do I still need? ___________________________']),
('Action plan',['Goal: __________________________________________________','Next action | Support or resource | Target date | Evidence of progress','_______________________________________________________','_______________________________________________________']),
('Review',['Review date: _______________','What worked? What changed? What will I do next?','_______________________________________________________'])])
add('interview-prep','Interview Preparation Template','templates','interview-templates','interviews','Organise your research and prepare relevant interview examples.',[
('Role and employer',['Role: ___________________ Employer: _____________________','Interview date, time and format: ___________________________','Three key requirements: __________________________________']),
('Your evidence',['Requirement | Example from work, study or volunteering','_______________________________________________________','_______________________________________________________','For each example, note the situation, task, action and result.']),
('Questions and logistics',['Questions I want to ask: __________________________________','Documents, meeting link or travel plan: _____________________','My introduction in three clear points: ______________________']),
('After the interview',['What went well? _________________________________________','What could I improve? ____________________________________','Agreed next step or follow-up date: _________________________'])])
add('cpd-record','CPD Record Template','templates','professional-development-templates','develop-skills','Record learning activities, evidence and how you put learning into practice.',[
('Development objective',['Skill or knowledge to develop: _____________________________','Why it matters to my role or goal: __________________________']),
('Learning record',['Date | Activity or course | Time spent | Evidence','_______________________________________________________','_______________________________________________________','_______________________________________________________']),
('Reflection',['What did I learn? ________________________________________','How will I apply it? ______________________________________','What changed after I used it? ______________________________']),
('Next review',['Next learning action: _____________________________________','Target date: ____________ Review date: ____________________'])])
add('job-search','Job Search Checklist','checklists','job-search-checklists','find-job','A step-by-step checklist to organise your job search.',[
('Set your direction',['[ ] Choose target roles and locations or working patterns.','[ ] List the skills and evidence you can offer.','[ ] Decide how you will track applications and follow-up dates.']),
('Prepare your application',['[ ] Tailor your CV to the role and check contact details.','[ ] Write a specific cover letter when requested.','[ ] Check the employer instructions, file types and closing date.']),
('Apply and follow up',['[ ] Save the job description and a copy of your application.','[ ] Record the role, employer, application date and status.','[ ] Follow up politely when appropriate and respect stated timelines.']),
('Review weekly',['[ ] Review which applications led to responses.','[ ] Identify one improvement to your CV, search or preparation.','[ ] Plan the next week and allow time for rest.'])])
add('interview-checklist','Interview Preparation Checklist','checklists','interview-checklists','interviews','Prepare before, during and after your interview.',[
('Before',['[ ] Read the job description and research the employer.','[ ] Prepare 3-5 examples showing relevant skills.','[ ] Practise a short introduction and questions for the interviewer.','[ ] Confirm the time, format, location or meeting link.']),
('On the day',['[ ] Check your connection, audio or travel plan.','[ ] Have the job description, CV and notes ready.','[ ] Listen carefully and ask for clarification if needed.','[ ] Use specific examples and explain your own contribution.']),
('After',['[ ] Record questions asked and useful feedback.','[ ] Note the next step and expected timeline.','[ ] Send a brief thank-you or follow-up when appropriate.','[ ] Choose one thing to improve for the next interview.'])])
add('career-change','Career Change Checklist','checklists','career-change-checklists','plan-career','Explore a career change with evidence and manageable next steps.',[
('Understand the change',['[ ] Write down what you want to change and what you want to keep.','[ ] List interests, values and working conditions that matter to you.','[ ] Identify transferable skills with examples.']),
('Explore options',['[ ] Research typical work, entry requirements and skill gaps.','[ ] Speak with people in the field where possible.','[ ] Test an option through a small project, learning or volunteering.']),
('Build a transition plan',['[ ] Identify practical commitments, time and support needs.','[ ] Choose a realistic first action and review date.','[ ] Tailor your CV to show transferable skills.','[ ] Review evidence before making a major commitment.'])])
add('remote-checklist','Work From Home Checklist','checklists','remote-work-checklists','develop-skills','Prepare your workspace, communication and daily routine.',[
('Workspace and equipment',['[ ] Set up a comfortable, usable work area.','[ ] Test internet, audio and required applications.','[ ] Follow employer requirements for secure access and device use.']),
('Communication',['[ ] Agree working hours, availability and response expectations.','[ ] Know how to ask for help and report progress.','[ ] Keep shared tasks, notes and deadlines current.']),
('Workday routine',['[ ] Choose a short priority list for the day.','[ ] Plan focused work periods and breaks.','[ ] Check progress and hand over unfinished work clearly.','[ ] Set a clear end-of-day routine.'])])
add('graduate-cv','Graduate CV Example','examples','cv-examples','find-job','A fictional example showing how a graduate can present relevant evidence.',[
('Alex Morgan | Graduate project assistant',['Fictional example: replace every detail with your own information.','alex@example.com | [Phone] | [Location]']),
('Profile',['Business graduate with experience coordinating a student research project and supporting a community event. Confident organising tasks, preparing clear notes and working with a small team.']),
('Relevant experience',['Student research project | Team coordinator','Created a shared task tracker and agreed weekly milestones with four classmates.','Combined survey findings into a concise presentation delivered by the deadline.','Community event | Volunteer','Helped organise attendee information and answered visitor questions.']),
('Education and skills',['[Degree], [Institution], [Dates]','Relevant modules: project planning, communication and data analysis.','Skills: task organisation, spreadsheet basics, presentation and teamwork.','Use specific, truthful achievements; do not copy the example as personal experience.'])])
add('cover-letter-example','Graduate Cover Letter Example','examples','cover-letter-examples','find-job','A fictional letter illustrating a clear opening, evidence and closing.',[
('Example letter',['Dear Hiring Team,','I am applying for the project assistant role. The opportunity to support organised, collaborative project delivery matches the work I enjoyed during my degree.']),
('Evidence paragraph',['For a student research project, I maintained a shared task tracker and coordinated weekly updates. Our group delivered its findings on time and presented a clear summary to the class. This strengthened my planning and communication skills.']),
('Closing paragraph',['I would welcome the opportunity to discuss how my organisation and willingness to learn could support your team. Thank you for considering my application.','Yours sincerely,','Alex Morgan (fictional example)']),
('Make it your own',['Replace the role, employer and evidence with truthful details.','Explain a specific reason for choosing the organisation.','Match your evidence to the role rather than using a generic letter.'])])
add('linkedin','LinkedIn Headline and Summary Examples','examples','linkedin-examples','find-job','Adapt these fictional examples to describe your own experience and direction.',[
('Headline examples',['Graduate business professional | Project coordination | Research and communication','Customer service adviser | Clear communication | Practical problem solving','Career changer | Operations experience | Developing data analysis skills']),
('Summary structure',['Start with your current direction and relevant experience.','Describe two strengths with evidence.','Finish with the kind of work or connections you want to explore.']),
('Fictional summary',['I am a business graduate interested in supporting well-organised projects. During a student research project, I coordinated tasks and helped turn findings into a clear presentation. I enjoy making information easy to follow and helping a team meet its commitments. I am exploring entry-level project support opportunities.']),
('Before publishing',['Use accurate skills and achievements. Keep private information out of public text.','Review spelling, dates and consistency with your CV.'])])
add('tell-me','Tell Me About Yourself: Answer Examples','examples','interview-answer-examples','interviews','Use a short, relevant introduction supported by honest evidence.',[
('A useful structure',['Present: your current role, study or direction.','Evidence: one or two experiences relevant to the job.','Future: why this role is a logical next step.']),
('Fictional graduate example',['I recently completed a business degree, where I enjoyed organising group projects. In our final research project, I maintained the task tracker and helped prepare a clear presentation. I am now looking for an entry-level project support role where I can use those skills and learn from an experienced team.']),
('Fictional customer service example',['I have experience helping customers understand their options and resolving everyday issues. I focus on listening, explaining clearly and following up on what I promise. This role interests me because it would let me support customers while developing deeper product knowledge.']),
('Your version',['My present situation: ____________________________________','My relevant example: _____________________________________','Why this role: __________________________________________','Practise the structure rather than memorising an invented story.'])])
add('career-worksheet','Career Planning Worksheet','worksheets','career-planning-worksheets','plan-career','Reflect on your interests, compare options and plan a practical next step.',[
('Understand yourself',['Activities I enjoy: _______________________________________','Strengths others notice: __________________________________','Values and working conditions that matter: __________________']),
('Compare options',['Career option | What interests me | What I need to learn','_______________________________________________________','_______________________________________________________','_______________________________________________________']),
('Test an option',['One low-commitment way to explore: ________________________','A person or source to learn from: __________________________','What evidence would help me decide? _______________________']),
('Next step',['Action: _______________________ Target date: ______________','Review what you learned: __________________________________'])])
add('smart-goals','SMART Goals Worksheet','worksheets','goal-setting-worksheets','develop-skills','Make a professional goal specific, measurable and reviewable.',[
('Draft your goal',['What do I want to achieve? ________________________________']),
('Make it SMART',['Specific: what action or outcome? __________________________','Measurable: how will I recognise progress? __________________','Achievable: what time, skills or support do I need? ____________','Relevant: why does it matter to my direction? _________________','Time-bound: target date and review date? ____________________']),
('Plan the work',['Step 1: __________________________ Date: ________________','Step 2: __________________________ Date: ________________','Potential obstacle and response: ___________________________']),
('Review',['Evidence of progress: _____________________________________','What will I adjust? _______________________________________'])])
add('communication','Communication Skills Assessment Worksheet','worksheets','skills-assessment-worksheets','develop-skills','Reflect on communication habits and choose a development action. This is not a diagnostic assessment.',[
('Rate recent examples',['Use 1 = rarely, 2 = sometimes, 3 = often. Base ratings on real situations.','Listening without interrupting: ___ Example: _________________','Explaining clearly for the audience: ___ Example: ______________','Checking understanding: ___ Example: _______________________','Giving respectful feedback: ___ Example: ____________________','Asking useful questions: ___ Example: _______________________']),
('Look for patterns',['A communication strength: _________________________________','A situation I find difficult: ________________________________','Feedback I could ask for: __________________________________']),
('Choose one action',['Habit to practise: ________________________________________','Where I will practise it: __________________________________','What improvement would look like: __________________________','Review date: ___________________________________________'])])
add('star-worksheet','STAR Method Worksheet','worksheets','interview-preparation-worksheets','interviews','Prepare an interview example using situation, task, action and result.',[
('Choose an example',['Skill or requirement to demonstrate: ________________________','Example from work, study or volunteering: ___________________']),
('Situation and task',['Situation: give only the context the interviewer needs.','_______________________________________________________','Task: what were you responsible for?','_______________________________________________________']),
('Action and result',['Action: what did you personally do, and why?','_______________________________________________________','_______________________________________________________','Result: what happened, and what did you learn?','_______________________________________________________']),
('Check the answer',['Is it relevant, clear and truthful? Is my own contribution clear?','If the result was mixed, explain what you learned and changed.'])])
add('development-planner','Professional Development Planner','downloads','','develop-skills','Plan learning goals, useful activities and evidence of progress.',[
('Development priorities',['Current responsibilities or career direction: _________________','Skills to strengthen: _____________________________________','One priority for this month: _______________________________']),
('Learning plan',['Learning activity | Target date | Support | Evidence','_______________________________________________________','_______________________________________________________','_______________________________________________________']),
('Put learning to work',['Where will I apply it? ____________________________________','Who could give feedback? _________________________________','What will I do differently? ________________________________']),
('Review progress',['What did I complete? _____________________________________','What effect did it have? __________________________________','Next action and review date: ______________________________'])])
add('job-toolkit','Job Search Toolkit','toolkits','','find-job','A CV checklist, application tracker and interview planner in one printable toolkit.',[
('1. CV and application checklist',['[ ] Choose a target role and read the requirements.','[ ] Tailor your profile, skills and evidence to that role.','[ ] Check dates, contact details and spelling.','[ ] Follow the requested file format and submission instructions.','[ ] Save a copy of the job description and application.']),
('2. Application tracker',['Employer | Role | Applied on | Status | Next action / date','_______________________________________________________','_______________________________________________________','_______________________________________________________','_______________________________________________________','_______________________________________________________']),
('3. Interview planner',['Role and employer: ______________________________________','Three relevant examples to prepare: ________________________','Questions to ask: ________________________________________','Time, location / link and preparation needed: _________________','Afterwards: what went well and what will I improve? ___________'])])
faqs=[
('career-faq-guide','Career Choices: Questions and Answers','career-faqs','plan-career','How do I choose a career?', 'Start with your interests, strengths and values. Compare the everyday work and entry requirements of a few options. Try a small project, course or conversation with someone in the field, then review what you learned before choosing your next step.'),
('job-faq-guide','Job Search: Questions and Answers','job-search-faqs','find-job','How can I organise my job search?', 'Choose a small set of target roles, tailor each application and keep a tracker with the employer, role, date, status and next action. Review responses regularly and improve one part of your approach at a time.'),
('interview-faq-guide','Interview Preparation: Questions and Answers','interview-faqs','interviews','How should I prepare for an interview?', 'Read the role requirements, research the employer and prepare a few truthful examples of relevant skills. Practise explaining your own actions and results. Confirm the format, time and meeting details, and prepare questions for the interviewer.'),
('remote-faq-guide','Remote Work: Questions and Answers','remote-work-faqs','develop-skills','How can I work effectively from home?', 'Agree priorities, working hours and communication expectations with your team. Use a suitable workspace, follow employer security requirements, plan focused work and breaks, and keep shared tasks and progress notes current.'),
('development-faq-guide','Professional Development: Questions and Answers','professional-development-faqs','develop-skills','How do I plan my professional development?', 'Choose a skill that matters to your current role or next goal. Plan a learning activity and a practical way to use it. Keep evidence, ask for feedback and set a review date so you can adjust the plan based on progress.')]
for key,title,sub,goal,question,answer in faqs:
    add(key,question,'faqs',sub,goal,title,[('Question',[question]),('Answer',[answer]),('Apply it',['One action I will take: ____________________________________','Support or information I need: _____________________________','Review date: ___________________________________________'])],answer)
styles=getSampleStyleSheet()
styles.add(ParagraphStyle(name='ResourceTitle',fontName='Helvetica-Bold',fontSize=25,leading=29,textColor=colors.HexColor('#091653'),spaceAfter=13))
styles.add(ParagraphStyle(name='ResourceSub',fontName='Helvetica',fontSize=10.5,leading=15,textColor=colors.HexColor('#425773'),spaceAfter=15))
styles.add(ParagraphStyle(name='ResourceHeading',fontName='Helvetica-Bold',fontSize=13,leading=17,textColor=colors.HexColor('#091653'),spaceBefore=13,spaceAfter=8))
styles.add(ParagraphStyle(name='ResourceBody',fontName='Helvetica',fontSize=10.5,leading=17,textColor=colors.HexColor('#283548'),spaceAfter=7))
def frame(canvas,doc):
    w,h=A4;canvas.setFillColor(colors.HexColor('#081452'));canvas.rect(0,h-44,w,44,fill=1,stroke=0);canvas.setFillColor(colors.white);canvas.setFont('Helvetica-Bold',11);canvas.drawString(42,h-28,'OHSC  |  Career Expert')
    canvas.setStrokeColor(colors.HexColor('#e0e5f0'));canvas.line(42,40,w-42,40);canvas.setFillColor(colors.HexColor('#69758d'));canvas.setFont('Helvetica',8);canvas.drawString(42,26,'Free career resource  |  Adapt to your own experience and goals');canvas.drawRightString(w-42,26,str(doc.page))
for r in records:
    pdf=OUT/(r['seed_key']+'.pdf');story=[Paragraph(escape(r['title']),styles['ResourceTitle']),Paragraph(escape(r['summary']),styles['ResourceSub'])]
    for i,(heading,lines) in enumerate(r['sections']):
        if r['seed_key']=='job-toolkit' and i:
            story.append(PageBreak())
        story.append(Paragraph(escape(heading),styles['ResourceHeading']))
        for line in lines:story.append(Paragraph(escape(line),styles['ResourceBody']))
    SimpleDocTemplate(str(pdf),pagesize=A4,rightMargin=44,leftMargin=44,topMargin=70,bottomMargin=57,title=r['title'],author='OHSC Career Expert').build(story,onFirstPage=frame,onLaterPages=frame)
    shutil.copyfile(pdf,DEST/pdf.name)
    text=r['title']+'\n'+r['summary']+'\n\n'+'\n\n'.join(h+'\n'+'\n'.join(lines) for h,lines in r['sections'])+'\n'
    (DEST/(r['seed_key']+'.txt')).write_text(text,encoding='utf-8')
    with zipfile.ZipFile(DEST/(r['seed_key']+'.docx'),'w',zipfile.ZIP_DEFLATED) as word:
        word.writestr('[Content_Types].xml','<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/></Types>')
        word.writestr('_rels/.rels','<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>')
        body=''.join('<w:p><w:r><w:t xml:space="preserve">'+escape(line)+'</w:t></w:r></w:p>' for line in text.splitlines())
        word.writestr('word/document.xml','<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body>'+body+'<w:sectPr><w:pgSz w:w="11906" w:h="16838"/><w:pgMar w:top="1134" w:right="1134" w:bottom="1134" w:left="1134"/></w:sectPr></w:body></w:document>')
    r['pdf']=r['seed_key']+'.pdf';r['text']=r['seed_key']+'.txt';del r['sections']
(DEST/'manifest.json').write_text(json.dumps(records,indent=2,ensure_ascii=False),encoding='utf-8')
print(f'Created {len(records)} relevant PDF resources with editable Word companions.')
