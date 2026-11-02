<?php
// New content is inserted only when its profession does not exist.
return json_decode(<<<'JSON'
[
  {
    "slug": "general",
    "title": "Common Interview Questions",
    "summary": "Questions that help you prepare for any role.",
    "image": "career-office.jpg",
    "order": 0,
    "questions": [
      [
        "Tell me about yourself.",
        "A strong answer should briefly cover your background, key skills and what motivates you. Keep it relevant to the role and end with a positive statement about what you can bring to the team.",
        "common"
      ],
      [
        "Why do you want this role?",
        "Connect the work to your interests and strengths. Mention something specific about the organisation and explain how you can contribute.",
        "common"
      ],
      [
        "What are your strengths?",
        "Choose two strengths relevant to the role. Give a short, concrete example of each and explain the benefit to your team or customers.",
        "common"
      ],
      [
        "Describe a challenge you handled.",
        "Use STAR: explain the situation, your task, the action you took and the result. Focus on your contribution and what you learned.",
        "behavioural"
      ],
      [
        "Tell me about a time you worked in a team.",
        "Describe the shared goal, your responsibility and how you communicated. Finish with the outcome and what made the teamwork effective.",
        "behavioural"
      ],
      [
        "How would you manage competing deadlines?",
        "Clarify priorities and impact, plan the work, communicate early and agree realistic deadlines. Explain how you would keep people updated.",
        "situational"
      ],
      [
        "What would you do if you made a mistake?",
        "Own the mistake, assess its impact, tell the right person and correct it promptly. Explain how you would prevent it happening again.",
        "situational"
      ],
      [
        "What questions would you ask us?",
        "Ask about the first priorities in the role, how success is measured and how the team works together. Choose questions you genuinely want answered.",
        "common"
      ]
    ]
  },
  {
    "slug": "customer-service",
    "title": "Customer Service",
    "summary": "Build confidence in helping customers and resolving problems.",
    "image": "career-service.jpg",
    "order": 5,
    "questions": [
      [
        "How would you handle an upset customer?",
        "A strong answer shows you can stay calm, listen and find a solution.\n1. Listen - allow the customer to explain without interrupting.\n2. Acknowledge - show understanding and empathy.\n3. Clarify - ask relevant questions to fully understand the issue.\n4. Resolve - offer a suitable solution within your role and company policies.\n5. Follow up - check the customer is satisfied and note any learning points.",
        "situational"
      ],
      [
        "Why do you want to work in customer service?",
        "Connect your interest in helping people with your communication and problem-solving skills. Give an example of when you helped someone successfully.",
        "common"
      ],
      [
        "How do you ensure customer satisfaction?",
        "Set clear expectations, listen carefully, check the customer understands the next step and follow through on promises. Use feedback to improve.",
        "common"
      ],
      [
        "Describe a time you dealt with a difficult customer.",
        "Use STAR to describe the issue, how you stayed calm, the practical steps you took and the result. Avoid blaming the customer.",
        "behavioural"
      ],
      [
        "What makes excellent customer service?",
        "Explain how empathy, accuracy, timely responses and ownership work together. Support your answer with a specific example.",
        "common"
      ],
      [
        "Tell me about a time you went the extra mile.",
        "Choose a realistic example where you spotted an unmet need and helped within company policy. Explain the positive impact on the customer.",
        "behavioural"
      ],
      [
        "What would you do if you could not solve a complaint?",
        "Explain the limits of your authority, escalate with clear notes, set a realistic expectation and take ownership of keeping the customer updated.",
        "situational"
      ],
      [
        "How do you stay organised during a busy shift?",
        "Prioritise urgent issues, keep accurate records, use the support system consistently and communicate with teammates about outstanding requests.",
        "common"
      ]
    ]
  },
  {
    "slug": "project-management",
    "title": "Project Management",
    "summary": "Prepare to discuss planning, teamwork and delivery.",
    "image": "career-data.jpg",
    "order": 6,
    "questions": [
      [
        "How do you plan a new project?",
        "Clarify the goal and success measures, identify stakeholders, agree scope and milestones, estimate resources and document major risks.",
        "common"
      ],
      [
        "How do you keep a project on track?",
        "Use a clear plan, review progress regularly and manage dependencies. Raise risks early and agree corrective action with the team.",
        "common"
      ],
      [
        "What would you do if a project fell behind schedule?",
        "Identify the cause and critical tasks, review options with the team, agree priorities and communicate a realistic recovery plan.",
        "situational"
      ],
      [
        "Describe a project you delivered successfully.",
        "Use STAR to explain the goal, your responsibility, how you organised delivery and the measurable result.",
        "behavioural"
      ],
      [
        "How do you manage project risks?",
        "Identify risks with the team, assess likelihood and impact, assign owners and review mitigation actions throughout the project.",
        "common"
      ],
      [
        "Tell me about a time you resolved a team disagreement.",
        "Explain both perspectives, how you helped the team focus on shared goals and the agreed action. Finish with the outcome.",
        "behavioural"
      ],
      [
        "How would you handle a major change in scope?",
        "Document the request, assess its impact on time, cost and quality, and seek the appropriate approval before changing the plan.",
        "situational"
      ],
      [
        "How do you communicate with stakeholders?",
        "Agree who needs what information and when. Use clear progress updates, flag decisions needed and adapt detail to the audience.",
        "common"
      ]
    ]
  }
]
JSON
, true);
