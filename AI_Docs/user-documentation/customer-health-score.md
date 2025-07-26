# Customer Health Score: User Guide

## 1. Overview

The Customer Health Score is an automated feature designed to provide an at-a-glance understanding of your customer relationships. By analyzing various interactions and activities, it assigns a numerical score (0-100) and a status indicator (Healthy, At Risk, Critical) to your Accounts and Contacts.

This tool helps you proactively identify at-risk customers, prioritize engagement efforts, and ultimately foster stronger, healthier business relationships.

## 2. Viewing Health Scores

Health scores are seamlessly integrated into the Accounts and Contacts modules for easy access.

### In List Views

When viewing lists of Accounts or Contacts, you will find two new columns:

*   **Health Score**: Displays the numerical score inside a color-coded badge for quick visual assessment. The column is sortable, allowing you to prioritize customers based on their health.
*   **Health Status**: Shows a distinct icon and color for each status level, providing immediate insight into the customer's standing.

Hovering over the score or status will reveal a tooltip with more detailed information.

### In Detail Views

On the detail page for an individual Account or Contact, the Health Score and Health Status are prominently displayed, offering interactive elements and tooltips for a comprehensive view.

## 3. Understanding the Health Score

The health score is categorized into three distinct statuses:

| Status | Score Range | Meaning | Icon |
| :--- | :--- | :--- | :--- |
| **Healthy** | 80-100 | The customer is actively engaged and on a positive track. | 🟢 `fa-circle` |
| **At Risk** | 50-79 | Engagement has slowed or there are potential risks. Requires attention. | 🟡 `fa-exclamation-triangle` |
| **Critical**| 0-49 | The customer is disengaged or there are significant issues. Immediate action is needed. | 🔴 `fa-exclamation-circle` |

## 4. How the Score is Calculated

The health score is calculated automatically based on a weighted algorithm that considers three key areas of customer interaction:

1.  **Activity Frequency (40% weight)**: Measures the recency of interactions such as calls, emails, meetings, and notes. More recent activity results in a higher score.
2.  **Email Engagement (30% weight)**: Analyzes email open and response rates over the last 90 days. Higher engagement indicates a healthier relationship.
3.  **Opportunity Progress (30% weight)**: For Accounts, this tracks the health of open opportunities. Progressing deals contribute positively, while stalled opportunities will lower the score.

## 5. Automatic Updates

The Customer Health Score is a dynamic metric. It updates automatically in two ways:

*   **Real-time**: Scores are recalculated immediately after a relevant activity is logged (e.g., a new call is recorded).
*   **Daily Recalculation**: A scheduled process runs daily to recalculate scores for all records, ensuring the data is always current and reflective of recent trends.

## 6. In-App Health Score Legend

For a quick reminder of what the scores and statuses mean, a comprehensive, tabbed legend is available within the CRM. This help documentation provides a detailed breakdown of the scoring system. 