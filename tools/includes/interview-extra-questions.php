<?php
// Additional editable questions; existing text and visibility are never replaced.
return json_decode(<<<'JSON'
{
  "customer-service": [
    [
      "How would you explain a policy a customer disagrees with?",
      "Stay calm and explain the reason in plain language. Acknowledge the inconvenience, describe any permitted alternatives and avoid promising an exception you cannot authorise.",
      "situational"
    ],
    [
      "Tell me about a time you turned a negative experience into a positive one.",
      "Use a specific example. Explain the complaint, how you listened and took ownership, the solution you agreed and how you checked the customer was satisfied.",
      "behavioural"
    ],
    [
      "How do you adapt your communication to different customers?",
      "Ask what the customer needs, use clear language and adjust your pace. Check understanding without making assumptions about their background or abilities.",
      "common"
    ],
    [
      "How would you handle several customers waiting at once?",
      "Acknowledge everyone, assess urgency and explain realistic waiting times. Follow the queue process and ask a colleague for support when needed.",
      "situational"
    ],
    [
      "What information should you record after a customer interaction?",
      "Record the issue, relevant facts, action taken, agreed next steps and any follow-up deadline. Keep notes objective and follow company rules for personal information.",
      "common"
    ],
    [
      "Describe a time you used feedback to improve your service.",
      "Explain the feedback, the change you made and how you checked it helped. Show that you accept feedback constructively and keep learning.",
      "behavioural"
    ],
    [
      "How would you respond if you gave a customer incorrect information?",
      "Acknowledge the error promptly, apologise, provide the correct information and explain the next step. Tell the appropriate colleague if further action is required.",
      "situational"
    ],
    [
      "How do you measure good customer service?",
      "Discuss accuracy, response time, resolution and customer feedback. Explain that meeting a target matters alongside treating customers fairly and solving the right problem.",
      "common"
    ]
  ],
  "healthcare": [
    [
      "What qualities help you provide person-centred care?",
      "Discuss listening, dignity, patience and clear communication. Give a practical example of adapting your support to a person’s needs within your role.",
      "common"
    ],
    [
      "How do you organise your work during a busy shift?",
      "Follow agreed priorities, communicate with the team and keep accurate records. Ask the appropriate supervisor for guidance when priorities conflict.",
      "common"
    ],
    [
      "Describe a time you showed empathy at work.",
      "Explain the situation, how you listened, the support you offered within your responsibilities and what you learned from the outcome.",
      "behavioural"
    ],
    [
      "What would you do if you noticed a concern about someone’s safety?",
      "Take the concern seriously, follow the organisation’s safety and reporting procedures, contact the appropriate responsible person and document facts accurately.",
      "situational"
    ]
  ],
  "business": [
    [
      "What does professional communication mean to you?",
      "Explain how you make messages clear, accurate and appropriate for the audience. Mention checking details, confirming responsibilities and following up.",
      "common"
    ],
    [
      "How do you check the accuracy of administrative work?",
      "Use a repeatable checklist, verify key figures against the source and review names, dates and attachments before sharing. Describe how you correct errors.",
      "common"
    ],
    [
      "Tell me about a time you supported a busy team.",
      "Describe the team’s goal, how you organised your tasks, the help you provided and the result. Be clear about your own contribution.",
      "behavioural"
    ],
    [
      "What would you do if important information was missing before a deadline?",
      "Identify exactly what is missing, contact the right person and explain the deadline. Flag the risk early and agree a realistic next step rather than guessing.",
      "situational"
    ]
  ],
  "education": [
    [
      "What does an inclusive learning environment look like?",
      "Describe clear expectations, respectful communication and varied ways to participate. Explain how you check that learners can access the activity.",
      "common"
    ],
    [
      "How do you know whether someone has understood a lesson?",
      "Ask open questions, use a short practical task and invite the learner to explain the idea in their own words. Adapt your support based on their response.",
      "common"
    ],
    [
      "Tell me about a time you adapted your approach to help someone learn.",
      "Explain the original difficulty, the change you made, why you chose it and the progress you observed. Include what you would improve next time.",
      "behavioural"
    ],
    [
      "What would you do if a learner was reluctant to participate?",
      "Avoid putting them on the spot. Check privately for barriers, offer a manageable way to contribute and follow relevant support procedures.",
      "situational"
    ]
  ],
  "technology": [
    [
      "How do you make your technical work easy for others to maintain?",
      "Describe clear naming, focused changes, useful documentation and reviews. Explain how you test the behaviour that matters to users.",
      "common"
    ],
    [
      "How do you decide which technical task to work on first?",
      "Compare user impact, urgency, dependencies and risk. Confirm priorities with the team and communicate if new information changes the plan.",
      "common"
    ],
    [
      "Describe a time you learned a new technology to solve a problem.",
      "Explain the problem, how you evaluated the tool, the small experiment you tried and what happened when you applied it.",
      "behavioural"
    ],
    [
      "What would you do if a release caused an unexpected issue?",
      "Assess the impact, alert the right people and follow the team’s incident process. Consider a safe rollback or fix, then document the cause and prevention steps.",
      "situational"
    ]
  ],
  "project-management": [
    [
      "How do you define success before starting a project?",
      "Agree the intended outcome, scope, acceptance criteria and measures with stakeholders. Record key assumptions and who approves the final deliverable.",
      "common"
    ],
    [
      "How do you keep meetings useful?",
      "Set an agenda and desired decisions, invite the right people and keep discussion focused. Finish with clear owners, actions and dates.",
      "common"
    ],
    [
      "Describe a time you managed a change in priorities.",
      "Explain what changed, how you assessed the impact, how you agreed the new plan and the result for the project.",
      "behavioural"
    ],
    [
      "What would you do if a key stakeholder was not responding?",
      "Clarify the decision needed and its deadline, try an agreed communication channel and flag the delivery impact. Escalate through the appropriate route if necessary.",
      "situational"
    ]
  ],
  "general": [
    [
      "How do you handle constructive feedback?",
      "Give an example of listening without interrupting, clarifying what to improve and putting the feedback into practice. Explain how you checked your progress.",
      "common"
    ],
    [
      "What motivates you to do your best work?",
      "Choose a genuine source of motivation that connects with the role. Support it with a brief example of when it helped you deliver good work.",
      "common"
    ],
    [
      "Tell me about a time you had to learn something quickly.",
      "Describe the task, how you identified the essentials, where you sought help and how you applied what you learned.",
      "behavioural"
    ],
    [
      "What would you do if you disagreed with a colleague’s approach?",
      "Ask about their reasoning, explain your concern respectfully and compare the options against the shared goal. Agree a way forward or seek appropriate guidance.",
      "situational"
    ]
  ]
}
JSON
, true);
