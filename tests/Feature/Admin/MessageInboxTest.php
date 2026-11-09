<?php

declare(strict_types=1);

use App\Models\Message;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('admin can view messages inbox and filter unread messages', function () {
    Message::factory()->create(['is_read' => false]);
    Message::factory()->create(['is_read' => true]);

    $response = $this->get(route('admin.messages.index'));
    $response->assertOk();
    $response->assertViewIs('admin.messages.index');

    $unreadResponse = $this->get(route('admin.messages.index', ['filter' => 'unread']));
    $unreadResponse->assertOk();
});

test('viewing a message marks it as read automatically', function () {
    $message = Message::factory()->create([
        'is_read' => false,
    ]);

    expect($message->is_read)->toBeFalse();

    $response = $this->get(route('admin.messages.show', $message));
    $response->assertOk();
    $response->assertViewIs('admin.messages.show');

    $message->refresh();
    expect($message->is_read)->toBeTrue();
});

test('admin can toggle message read status', function () {
    $message = Message::factory()->create([
        'is_read' => true,
    ]);

    $response = $this->patch(route('admin.messages.toggle-read', $message));
    $response->assertRedirect();

    $message->refresh();
    expect($message->is_read)->toBeFalse();

    $this->patch(route('admin.messages.toggle-read', $message));
    $message->refresh();
    expect($message->is_read)->toBeTrue();
});

test('admin can delete a message', function () {
    $message = Message::factory()->create();

    $response = $this->delete(route('admin.messages.destroy', $message));
    $response->assertRedirect(route('admin.messages.index'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('messages', ['id' => $message->id]);
});
