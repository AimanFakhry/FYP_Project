<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\LessonGroup;
use App\Models\Lesson;
use App\Models\LessonFillInTheBlank;
use App\Models\LessonSandbox;
use App\Models\LessonTextOnly;
use App\Models\Achievement;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LessonSeeder extends Seeder
{
    public function run()
    {
        // 1. Clean Database
        Schema::disableForeignKeyConstraints();
        DB::table('lesson_user')->truncate();
        DB::table('lesson_fill_in_the_blanks')->truncate();
        DB::table('lesson_sandboxes')->truncate();
        DB::table('lesson_text_onlies')->truncate();
        DB::table('lessons')->truncate();
        DB::table('lesson_groups')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Fetch References
        $cpp = Course::where('name', 'C++')->first();
        $php = Course::where('name', 'PHP')->first();
        $js = Course::where('name', 'JavaScript')->first();

        $achFirst = Achievement::where('name', 'First Steps')->first();
        $achCpp = Achievement::where('name', 'C++ Novice')->first();
        $achPhp = Achievement::where('name', 'PHP Novice')->first();
        $achJs = Achievement::where('name', 'JS Novice')->first();

        // 3. Define Curriculum Content (Inspired by SoloLearn structure, expanded and improved)
        // FORMAT: 'Course Name' => [ 'Part Title' => [ [Lesson Data], [Lesson Data] ] ]
        $curriculum = [
            'C++' => [
                'Module 1: Introduction to C++' => [
                    [
                        'title' => 'What is C++?',
                        'type' => 'text_only',
                        'content' => '<h3>Welcome to C++</h3><p>C++ is a powerful, high-performance programming language developed by Bjarne Stroustrup in 1983. It\'s an extension of the C language, adding object-oriented features while maintaining low-level control over hardware.</p><p>C++ is widely used for system/software development, game development, embedded systems, and more. It gives you direct access to memory and system resources, making it ideal for performance-critical applications.</p><p><strong>Key Features:</strong></p><ul><li>Object-Oriented Programming (OOP)</li><li>High Performance and Efficiency</li><li>Cross-Platform Compatibility</li><li>Rich Standard Library</li></ul>',
                        'achievement' => $achFirst // Award on first lesson
                    ],
                    [
                        'title' => 'Your First C++ Program',
                        'type' => 'sandbox',
                        'starting_code' => "#include <iostream>\nusing namespace std;\n\nint main() {\n    cout << \"Hello, World!\" << endl;\n    return 0;\n}",
                        'content' => '<p>Every C++ program starts with the <code>main()</code> function. The <code>cout</code> object (from <code>iostream</code>) is used to output text to the console.</p><p><strong>Task:</strong> Modify the code to print your name instead of "Hello, World!". Run it to see the output.</p>'
                    ],
                    [
                        'title' => 'Headers and Namespaces',
                        'type' => 'fill_in_the_blank',
                        'question' => 'To use input/output functions like cout, we include the ____ header.',
                        'answer' => 'iostream'
                    ],
                    [
                        'title' => 'Comments in C++',
                        'type' => 'sandbox',
                        'starting_code' => "#include <iostream>\nusing namespace std;\n\nint main() {\n    // This is a single-line comment\n    cout << \"Comments are ignored by the compiler!\" << endl;\n    /* This is a\n       multi-line comment */\n    return 0;\n}",
                        'content' => '<p>Comments are used to explain code and are ignored by the compiler. Single-line comments start with <code>//</code>, and multi-line comments are enclosed in <code>/* */</code>.</p><p><strong>Task:</strong> Add a comment explaining what the main function does.</p>'
                    ]
                ],
                'Module 2: Data Types and Variables' => [
                    [
                        'title' => 'Data Types',
                        'type' => 'text_only',
                        'content' => '<h3>Understanding Data Types</h3><p>Data types specify the type of data a variable can hold. C++ has built-in types:</p><ul><li><strong>int</strong>: Whole numbers (e.g., 42)</li><li><strong>double</strong>: Floating-point numbers (e.g., 3.14)</li><li><strong>char</strong>: Single characters (e.g., \'A\')</li><li><strong>string</strong>: Sequences of characters (e.g., "Hello")</li><li><strong>bool</strong>: True or false values</li></ul><p>Choosing the right data type is crucial for memory efficiency and performance.</p>'
                    ],
                    [
                        'title' => 'Declaring Variables',
                        'type' => 'sandbox',
                        'starting_code' => "#include <iostream>\nusing namespace std;\n\nint main() {\n    int age = 25;\n    double pi = 3.14159;\n    char grade = 'A';\n    string name = \"John\";\n    bool isStudent = true;\n    \n    cout << \"Name: \" << name << \", Age: \" << age << endl;\n    return 0;\n}",
                        'content' => '<p>Variables are declared with a data type followed by the variable name and an optional initial value.</p><p><strong>Task:</strong> Declare a variable for your favorite number (int) and print it.</p>'
                    ],
                    [
                        'title' => 'Variable Naming Rules',
                        'type' => 'fill_in_the_blank',
                        'question' => 'Variable names cannot start with a ____.',
                        'answer' => 'number'
                    ],
                    [
                        'title' => 'Constants',
                        'type' => 'sandbox',
                        'starting_code' => "#include <iostream>\nusing namespace std;\n\nint main() {\n    const double PI = 3.14159;\n    const int DAYS_IN_WEEK = 7;\n    \n    cout << \"PI: \" << PI << \", Days in week: \" << DAYS_IN_WEEK << endl;\n    // PI = 3.14; // This would cause an error\n    return 0;\n}",
                        'content' => '<p>Constants are variables whose values cannot be changed after initialization. Use the <code>const</code> keyword.</p><p><strong>Task:</strong> Define a constant for the speed of light and print it.</p>',
                        'achievement' => $achCpp // Award on completion of Module 2
                    ]
                ],
                'Module 3: Operators and Expressions' => [
                    [
                        'title' => 'Arithmetic Operators',
                        'type' => 'text_only',
                        'content' => '<h3>Basic Operators</h3><p>C++ supports standard arithmetic operators:</p><ul><li><strong>+</strong>: Addition</li><li><strong>-</strong>: Subtraction</li><li><strong>*</strong>: Multiplication</li><li><strong>/</strong>: Division</li><li><strong>%</strong>: Modulo (remainder)</li></ul><p>These can be used with variables and constants to form expressions.</p>'
                    ],
                    [
                        'title' => 'Using Operators',
                        'type' => 'sandbox',
                        'starting_code' => "#include <iostream>\nusing namespace std;\n\nint main() {\n    int a = 10, b = 3;\n    cout << \"Addition: \" << (a + b) << endl;\n    cout << \"Subtraction: \" << (a - b) << endl;\n    cout << \"Multiplication: \" << (a * b) << endl;\n    cout << \"Division: \" << (a / b) << endl;\n    cout << \"Modulo: \" << (a % b) << endl;\n    return 0;\n}",
                        'content' => '<p><strong>Task:</strong> Calculate and print the area of a rectangle with width 5 and height 10.</p>'
                    ],
                    [
                        'title' => 'Comparison Operators',
                        'type' => 'fill_in_the_blank',
                        'question' => 'The operator ____ checks if two values are equal.',
                        'answer' => '=='
                    ]
                ]
            ],
            'PHP' => [
                'Module 1: PHP Basics' => [
                    [
                        'title' => 'Introduction to PHP',
                        'type' => 'text_only',
                        'content' => '<h3>What is PHP?</h3><p>PHP (Hypertext Preprocessor) is a server-side scripting language designed for web development. It\'s embedded in HTML and executed on the server, generating dynamic web content.</p><p>PHP is open-source, easy to learn, and powers popular sites like Facebook and WordPress. It integrates seamlessly with databases like MySQL.</p><p><strong>Advantages:</strong></p><ul><li>Server-side execution</li><li>Database integration</li><li>Cross-platform</li><li>Large community and resources</li></ul>'
                    ],
                    [
                        'title' => 'PHP Syntax',
                        'type' => 'fill_in_the_blank',
                        'question' => 'PHP code is enclosed in ____ tags.',
                        'answer' => '<?php ?>'
                    ],
                    [
                        'title' => 'Hello World in PHP',
                        'type' => 'sandbox',
                        'starting_code' => "<?php\n    echo \"Hello, World!\";\n?>",
                        'content' => '<p>The <code>echo</code> statement outputs text to the browser. PHP code runs on the server before the page is sent to the client.</p><p><strong>Task:</strong> Change the message to "Welcome to PHP!" and run it.</p>'
                    ],
                    [
                        'title' => 'Comments in PHP',
                        'type' => 'sandbox',
                        'starting_code' => "<?php\n    // Single-line comment\n    echo \"This is PHP!\"; // Inline comment\n    /* Multi-line\n       comment */\n?>",
                        'content' => '<p>Comments help document code. Single-line with <code>//</code> or <code>#</code>, multi-line with <code>/* */</code>.</p><p><strong>Task:</strong> Add a comment explaining the echo statement.</p>'
                    ]
                ],
                'Module 2: Variables and Data Types' => [
                    [
                        'title' => 'PHP Variables',
                        'type' => 'text_only',
                        'content' => '<h3>Working with Variables</h3><p>In PHP, variables start with <code>$</code> and are case-sensitive. They can hold different data types:</p><ul><li><strong>String</strong>: Text (e.g., "Hello")</li><li><strong>Integer</strong>: Whole numbers (e.g., 42)</li><li><strong>Float</strong>: Decimal numbers (e.g., 3.14)</li><li><strong>Boolean</strong>: True/False</li><li><strong>Array</strong>: Collection of values</li></ul><p>PHP is loosely typed, so variables can change types dynamically.</p>'
                    ],
                    [
                        'title' => 'Variable Examples',
                        'type' => 'sandbox',
                        'starting_code' => "<?php\n    \$name = \"Alice\";\n    \$age = 30;\n    \$height = 5.6;\n    \$isStudent = true;\n    \n    echo \"Name: \$name, Age: \$age\";\n?>",
                        'content' => '<p><strong>Task:</strong> Create a variable for your city and print a sentence including it.</p>',
                        'achievement' => $achPhp
                    ],
                    [
                        'title' => 'Variable Scope',
                        'type' => 'fill_in_the_blank',
                        'question' => 'Variables declared inside a function are ____ to that function.',
                        'answer' => 'local'
                    ]
                ],
                'Module 3: Operators' => [
                    [
                        'title' => 'Arithmetic Operators',
                        'type' => 'text_only',
                        'content' => '<h3>PHP Operators</h3><p>PHP supports various operators for calculations and comparisons:</p><ul><li><strong>+ , - , * , /</strong>: Basic math</li><li><strong>%</strong>: Modulo</li><li><strong>== , != , < , ></strong>: Comparisons</li><li><strong>. </strong>: String concatenation</li></ul>'
                    ],
                    [
                        'title' => 'Using Operators',
                        'type' => 'sandbox',
                        'starting_code' => "<?php\n    \$a = 10;\n    \$b = 5;\n    echo \"Sum: \" . (\$a + \$b) . \"<br>\";\n    echo \"Product: \" . (\$a * \$b);\n?>",
                        'content' => '<p><strong>Task:</strong> Calculate the average of 8, 12, and 15.</p>'
                    ]
                ]
            ],
            'JavaScript' => [
                'Module 1: JavaScript Fundamentals' => [
                    [
                        'title' => 'What is JavaScript?',
                        'type' => 'text_only',
                        'content' => '<h3>Introduction to JavaScript</h3><p>JavaScript is a high-level, interpreted programming language that adds interactivity to websites. It\'s one of the core technologies of the web, alongside HTML and CSS.</p><p>Originally created for client-side scripting, JS now runs on servers (Node.js), mobile apps, and more. It\'s dynamic, prototype-based, and supports OOP.</p><p><strong>Uses:</strong></p><ul><li>Web interactivity</li><li>Server-side development</li><li>Game development</li><li>Mobile apps</li></ul>'
                    ],
                    [
                        'title' => 'Hello World',
                        'type' => 'sandbox',
                        'starting_code' => "console.log('Hello, World!');",
                        'content' => '<p><code>console.log()</code> outputs to the browser console. Open developer tools (F12) to see it.</p><p><strong>Task:</strong> Log your favorite programming language.</p>'
                    ],
                    [
                        'title' => 'JavaScript in HTML',
                        'type' => 'fill_in_the_blank',
                        'question' => 'JavaScript code is placed in ____ tags.',
                        'answer' => '<script>'
                    ],
                    [
                        'title' => 'Comments',
                        'type' => 'sandbox',
                        'starting_code' => "// Single-line comment\nconsole.log('JavaScript is fun!');\n/* Multi-line\n   comment */",
                        'content' => '<p>Comments are ignored by the engine. Use <code>//</code> for single-line or <code>/* */</code> for multi-line.</p><p><strong>Task:</strong> Add a comment to the code.</p>'
                    ]
                ],
                'Module 2: Variables and Data Types' => [
                    [
                        'title' => 'Variables in JS',
                        'type' => 'text_only',
                        'content' => '<h3>Declaring Variables</h3><p>JavaScript variables can be declared with <code>var</code>, <code>let</code>, or <code>const</code>. Use <code>let</code> for block-scoped variables and <code>const</code> for constants.</p><p>Data types include:</p><ul><li><strong>string</strong>: Text</li><li><strong>number</strong>: Numbers</li><li><strong>boolean</strong>: True/False</li><li><strong>object</strong>: Collections</li><li><strong>undefined</strong>: Unassigned</li></ul>'
                    ],
                    [
                        'title' => 'Variable Examples',
                        'type' => 'sandbox',
                        'starting_code' => "let name = 'Bob';\nconst age = 25;\nvar isCoder = true;\n\nconsole.log(name, age, isCoder);",
                        'content' => '<p><strong>Task:</strong> Declare a variable for your hobby and log it.</p>',
                        'achievement' => $achJs
                    ],
                    [
                        'title' => 'Dynamic Typing',
                        'type' => 'fill_in_the_blank',
                        'question' => 'In JavaScript, variables can change ____ without redeclaration.',
                        'answer' => 'type'
                    ]
                ],
                'Module 3: Operators' => [
                    [
                        'title' => 'JavaScript Operators',
                        'type' => 'text_only',
                        'content' => '<h3>Operators Overview</h3><p>JS supports arithmetic, comparison, and logical operators:</p><ul><li><strong>+ , - , * , /</strong>: Math</li><li><strong>=== , !==</strong>: Strict equality</li><li><strong>&& , || , !</strong>: Logical</li></ul><p>Be careful with type coercion!</p>'
                    ],
                    [
                        'title' => 'Operator Practice',
                        'type' => 'sandbox',
                        'starting_code' => "let x = 10;\nlet y = 5;\nconsole.log('Sum:', x + y);\nconsole.log('Equal:', x === y);",
                        'content' => '<p><strong>Task:</strong> Check if 15 is greater than 10 and log the result.</p>'
                    ]
                ]
            ]
        ];

        // 4. Process the Curriculum Array
        $this->processCurriculum($curriculum, $cpp, $php, $js);
    }

    private function processCurriculum($curriculum, $cpp, $php, $js)
{
    foreach ($curriculum as $courseName => $parts) {
        
        // Resolve Course ID
        $courseId = null;
        if ($courseName === 'C++') $courseId = $cpp?->id;
        if ($courseName === 'PHP') $courseId = $php?->id;
        if ($courseName === 'JavaScript') $courseId = $js?->id;

        if (!$courseId) continue;

        $groupOrder = 1;

        foreach ($parts as $partTitle => $lessons) {
            
            // Create Lesson Group (Part)
            $group = LessonGroup::create([
                'course_id' => $courseId,
                'title' => $partTitle,
                'order' => $groupOrder++
            ]);

            $lessonOrder = 1;

            foreach ($lessons as $lData) {
                
                // Create Lesson
                $lesson = Lesson::create([
                    'lesson_group_id' => $group->id,
                    'title' => $lData['title'],
                    'content' => $lData['content'] ?? 'Instructions inside activity.', // Fallback content
                    'order' => $lessonOrder++,
                    'activity_type' => $lData['type'],
                    'achievement_id' => isset($lData['achievement']) ? $lData['achievement']->id : null,
                ]);

                // Create Activity Data based on Type
                if ($lData['type'] === 'text_only') {
                    // Create unique content for LessonTextOnly (not mirroring lessons.content)
                    $extendedContent = $lData['content'] . '<p><strong>Deep Dive:</strong> Explore more by practicing the concepts in the following sandbox lesson. For further reading, check official documentation.</p>';
                    LessonTextOnly::create([
                        'lesson_id' => $lesson->id,
                        'content' => $extendedContent // Now different from lessons.content
                    ]);
                } 
                elseif ($lData['type'] === 'fill_in_the_blank') {
                    LessonFillInTheBlank::create([
                        'lesson_id' => $lesson->id,
                        'question' => $lData['question'],
                        'answer' => $lData['answer']
                    ]);
                } 
                elseif ($lData['type'] === 'sandbox') {
                    LessonSandbox::create([
                        'lesson_id' => $lesson->id,
                        'starting_code' => $lData['starting_code']
                    ]);
                }
            }
        }
    }
}
}