# Prevent-Email-Crawler
It blocks the email crawler from seeing all your emails in the website and allows human user to see and copy the emails

#How this prevents the crawler from seeing the email

Most of the crawler technology is based on text, so if you can use image then crawlers wont see  and the humans will. Email Crawlers or any crawlers can identify the email by its unique markers in this specific format 'name@domain.com'.
So we can mask the emails '@' and '.' (dot) with an image to hide the email. 

But a problem with this approach is that a human user who wants to copy paste the email, will not be able to do so. This code solves that issue by using on the fly javascript.

#Features

1. Blocks Email Crawlers
2. Allow Human user to copy the email
3. Auto Font
4. Auto Font Size 
5. Auto Font Color
6. Auto Font Style

#How to use 

For email - email5@testing.com,  use html <strong> &lt;li class=&quot;crawlEsc&quot;&gt;email5&lt;img/&gt;testing&lt;img/&gt;com&lt;/li&gt; </strong>. Replace '@' and '.' with <strong>&lt;img/&gt;</strong> tag and group the email in a custom class name like here "crawlEsc". The code will automatically assign an image of '@' and '.' correctly to the email.




