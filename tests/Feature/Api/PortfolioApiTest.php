<?php

declare(strict_types=1);

use App\Models\Credential;
use App\Models\Experience;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('GET /api/portfolio returns 200 with complete portfolio payload', function () {
    // Arrange: Create test data
    Project::factory()->create([
        'title' => 'Project Alpha',
        'is_featured' => true,
    ]);

    Credential::factory()->certificate()->create(['title' => 'AWS Certified']);
    Credential::factory()->achievement()->create(['title' => 'Hackathon Winner']);
    Credential::factory()->techStack()->create(['title' => 'React']);

    Experience::factory()->education()->create(['institution' => 'Tech University']);
    Experience::factory()->experience()->create(['institution' => 'Dev Studio']);

    // Act: Request the portfolio endpoint
    $response = $this->getJson('/api/portfolio');

    // Assert: Verify status and structure
    $response->assertOk()
        ->assertJson([
            'status' => 'success',
            'message' => 'Data portofolio berhasil diambil.',
        ])
        ->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'stats' => [
                    'projects_count',
                    'certificates_count',
                    'awards_count',
                ],
                'education',
                'experience',
                'projects',
                'certificates',
                'achievements',
                'tech_stack',
            ],
        ]);

    $data = $response->json('data');
    expect($data['stats']['projects_count'])->toBe(1)
        ->and($data['stats']['certificates_count'])->toBe(1)
        ->and($data['stats']['awards_count'])->toBe(1)
        ->and(count($data['projects']))->toBe(1)
        ->and(count($data['certificates']))->toBe(1)
        ->and(count($data['achievements']))->toBe(1)
        ->and(count($data['tech_stack']))->toBe(1)
        ->and(count($data['education']))->toBe(1)
        ->and(count($data['experience']))->toBe(1);
});

test('GET /api/portfolio only returns featured projects in projects array', function () {
    Project::factory()->create([
        'title' => 'Featured Project',
        'is_featured' => true,
    ]);

    Project::factory()->create([
        'title' => 'Hidden Project',
        'is_featured' => false,
    ]);

    $response = $this->getJson('/api/portfolio');

    $response->assertOk();
    $projects = $response->json('data.projects');

    expect(count($projects))->toBe(1)
        ->and($projects[0]['title'])->toBe('Featured Project')
        ->and($response->json('data.stats.projects_count'))->toBe(2);
});

test('GET /api/portfolio returns featured projects ordered by sort_order ascending', function () {
    Project::factory()->create([
        'title' => 'Project Kedua',
        'is_featured' => true,
        'sort_order' => 20,
    ]);

    Project::factory()->create([
        'title' => 'Project Pertama',
        'is_featured' => true,
        'sort_order' => 5,
    ]);

    $response = $this->getJson('/api/portfolio');
    $response->assertOk();

    $projects = $response->json('data.projects');
    expect($projects[0]['title'])->toBe('Project Pertama')
        ->and($projects[1]['title'])->toBe('Project Kedua');
});
