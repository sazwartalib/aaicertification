<?php

namespace Database\Seeders;

use App\Enums\CertificateStatus;
use App\Enums\CourseLevel;
use App\Enums\DeliveryMode;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'AAI Administrator',
            'email' => 'admin@aaicertification.test',
            'password' => 'password',
        ]);

        collect([
            ['name' => 'Quality Management', 'description' => 'ISO management systems, auditing, and continual improvement programs.'],
            ['name' => 'Information Security', 'description' => 'Cybersecurity, ISO/IEC 27001, and data protection certifications.'],
            ['name' => 'Occupational Safety & Health', 'description' => 'Workplace safety, risk assessment, and OSH management training.'],
            ['name' => 'Project Management', 'description' => 'Project delivery frameworks, agile practice, and PMO capability.'],
            ['name' => 'Professional Skills', 'description' => 'Leadership, communication, and workplace effectiveness programs.'],
        ])->each(fn (array $data): Category => Category::create([
            ...$data,
            'slug' => Str::slug($data['name']),
        ]));

        $courses = [
            [
                'category' => 'Quality Management',
                'title' => 'ISO 9001:2015 Lead Auditor',
                'summary' => 'A CQI and IRCA certified course preparing you to plan, conduct, and report first, second, and third-party audits of quality management systems.',
                'level' => CourseLevel::Advanced,
                'delivery_mode' => DeliveryMode::Hybrid,
                'duration' => '5 days',
                'price' => 3200,
                'accreditation_body' => 'CQI | IRCA',
                'is_featured' => true,
            ],
            [
                'category' => 'Quality Management',
                'title' => 'ISO 9001:2015 Internal Auditor',
                'summary' => 'Build the skills to run an effective internal audit programme and drive continual improvement across your organisation.',
                'level' => CourseLevel::Intermediate,
                'delivery_mode' => DeliveryMode::Online,
                'duration' => '2 days',
                'price' => 1250,
                'accreditation_body' => 'Exemplar Global',
                'is_featured' => false,
            ],
            [
                'category' => 'Information Security',
                'title' => 'ISO/IEC 27001:2022 Lead Implementer',
                'summary' => 'Master the implementation and management of an Information Security Management System (ISMS) based on ISO/IEC 27001.',
                'level' => CourseLevel::Advanced,
                'delivery_mode' => DeliveryMode::Hybrid,
                'duration' => '5 days',
                'price' => 3400,
                'accreditation_body' => 'PECB',
                'is_featured' => true,
            ],
            [
                'category' => 'Information Security',
                'title' => 'Certified Cybersecurity Awareness Practitioner',
                'summary' => 'Practical, role-based cybersecurity training covering phishing, social engineering, secure remote work, and incident reporting.',
                'level' => CourseLevel::Beginner,
                'delivery_mode' => DeliveryMode::Online,
                'duration' => '1 day',
                'price' => 650,
                'accreditation_body' => null,
                'is_featured' => false,
            ],
            [
                'category' => 'Occupational Safety & Health',
                'title' => 'ISO 45001:2018 Lead Auditor',
                'summary' => 'Develop the competence to audit occupational health and safety management systems against ISO 45001 requirements.',
                'level' => CourseLevel::Advanced,
                'delivery_mode' => DeliveryMode::InPerson,
                'duration' => '5 days',
                'price' => 3200,
                'accreditation_body' => 'CQI | IRCA',
                'is_featured' => true,
            ],
            [
                'category' => 'Occupational Safety & Health',
                'title' => 'Workplace Risk Assessment & HIRARC',
                'summary' => 'Hands-on training in Hazard Identification, Risk Assessment and Risk Control aligned with DOSH guidelines.',
                'level' => CourseLevel::Intermediate,
                'delivery_mode' => DeliveryMode::InPerson,
                'duration' => '2 days',
                'price' => 1400,
                'accreditation_body' => 'HRD Corp Claimable',
                'is_featured' => false,
            ],
            [
                'category' => 'Project Management',
                'title' => 'Project Management Professional (PMP)® Exam Prep',
                'summary' => '35 contact hours of intensive preparation for the PMI Project Management Professional certification exam.',
                'level' => CourseLevel::Advanced,
                'delivery_mode' => DeliveryMode::Hybrid,
                'duration' => '5 days',
                'price' => 2900,
                'accreditation_body' => 'PMI Authorized Training Partner',
                'is_featured' => true,
            ],
            [
                'category' => 'Project Management',
                'title' => 'Agile & Scrum Foundations',
                'summary' => 'Understand agile values, Scrum roles, events and artefacts, and how to run your first sprint with confidence.',
                'level' => CourseLevel::Beginner,
                'delivery_mode' => DeliveryMode::Online,
                'duration' => '2 days',
                'price' => 1100,
                'accreditation_body' => null,
                'is_featured' => false,
            ],
            [
                'category' => 'Professional Skills',
                'title' => 'Leadership & People Management Essentials',
                'summary' => 'A practical programme for new and aspiring managers covering delegation, feedback, coaching, and team performance.',
                'level' => CourseLevel::Intermediate,
                'delivery_mode' => DeliveryMode::InPerson,
                'duration' => '3 days',
                'price' => 1800,
                'accreditation_body' => 'HRD Corp Claimable',
                'is_featured' => false,
            ],
            [
                'category' => 'Professional Skills',
                'title' => 'Business Communication & Professional Writing',
                'summary' => 'Sharpen written and verbal communication for reports, presentations, and stakeholder engagement.',
                'level' => CourseLevel::Beginner,
                'delivery_mode' => DeliveryMode::Online,
                'duration' => '1 day',
                'price' => 700,
                'accreditation_body' => null,
                'is_featured' => false,
            ],
        ];

        $created = collect($courses)->values()->map(function (array $data, int $index): Course {
            $body = "<p>{$data['summary']}</p>"
                .'<h3>Who should attend</h3>'
                .'<p>Managers, practitioners, consultants, and internal specialists who are responsible for implementing, maintaining, or auditing management systems and want a recognised professional credential.</p>'
                .'<h3>Learning outcomes</h3>'
                .'<ul><li>Interpret the requirements of the relevant standard in a real organisational context.</li>'
                .'<li>Plan and deliver activities using a structured, risk-based methodology.</li>'
                .'<li>Prepare clear, defensible documentation and reports.</li>'
                .'<li>Apply best practice to drive measurable improvement.</li></ul>'
                .'<h3>Assessment & certification</h3>'
                .'<p>Participants are assessed through continuous exercises and a final examination. Successful candidates receive an AAI Certification certificate with a unique, verifiable certificate number.</p>';

            return Course::create([
                'category_id' => ($this->firstCategoryId($data['category'])),
                'title' => $data['title'],
                'slug' => Str::slug(str_replace([':', '/', '®'], ' ', $data['title'])),
                'summary' => $data['summary'],
                'description' => $body,
                'level' => $data['level'],
                'delivery_mode' => $data['delivery_mode'],
                'duration' => $data['duration'],
                'price' => $data['price'],
                'accreditation_body' => $data['accreditation_body'],
                'is_featured' => $data['is_featured'],
                'is_published' => true,
                'sort_order' => $index,
            ]);
        });

        Enrollment::factory(18)
            ->recycle($created)
            ->create();

        Enrollment::factory(6)
            ->pending()
            ->recycle($created)
            ->create();

        $created->each(function (Course $course): void {
            Certificate::factory(random_int(3, 8))
                ->for($course)
                ->state(fn () => ['course_title' => $course->title])
                ->create();
        });

        // Well-known sample certificates for demonstrating public verification.
        Certificate::create([
            'certificate_number' => 'AAI-2025-00001',
            'recipient_name' => 'Nurul Aisyah binti Rahman',
            'course_id' => $created->firstWhere('title', 'ISO 9001:2015 Lead Auditor')?->id,
            'course_title' => 'ISO 9001:2015 Lead Auditor',
            'issued_at' => Carbon::parse('2025-03-14'),
            'expires_at' => Carbon::parse('2028-03-13'),
            'status' => CertificateStatus::Valid,
            'grade' => 'Distinction',
        ]);

        Certificate::create([
            'certificate_number' => 'AAI-2024-00742',
            'recipient_name' => 'James Anthony Lim',
            'course_id' => $created->firstWhere('title', 'ISO/IEC 27001:2022 Lead Implementer')?->id,
            'course_title' => 'ISO/IEC 27001:2022 Lead Implementer',
            'issued_at' => Carbon::parse('2024-09-02'),
            'expires_at' => Carbon::parse('2027-09-01'),
            'status' => CertificateStatus::Valid,
            'grade' => 'Merit',
        ]);

        Certificate::create([
            'certificate_number' => 'AAI-2023-00318',
            'recipient_name' => 'Siti Farah binti Osman',
            'course_id' => $created->firstWhere('title', 'ISO 45001:2018 Lead Auditor')?->id,
            'course_title' => 'ISO 45001:2018 Lead Auditor',
            'issued_at' => Carbon::parse('2023-06-20'),
            'expires_at' => Carbon::parse('2026-06-19'),
            'status' => CertificateStatus::Revoked,
            'grade' => 'Pass',
        ]);
    }

    private function firstCategoryId(string $name): ?int
    {
        return Category::where('name', $name)->value('id');
    }
}
