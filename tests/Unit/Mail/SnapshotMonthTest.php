<?php

declare(strict_types=1);

namespace Tipoff\Reviews\Tests\Unit\Mail;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tipoff\Authorization\Models\User;
use Tipoff\Locations\Models\Location;
use Tipoff\Locations\Models\Market;
use Tipoff\Reviews\Mail\SnapshotMonth;
use Tipoff\Reviews\Models\Competitor;
use Tipoff\Reviews\Tests\TestCase;

class SnapshotMonthTest extends TestCase
{
    use DatabaseTransactions;

    //Todo: Need to figure out how to test markdown content

    /** @test */
    public function email()
    {
        Mail::fake();
        Mail::assertNothingSent();

        $market = Market::factory()->create();
        Location::factory()->create([
            'market_id' => $market->id,
            'manager_id' => User::factory()->create()->id,
        ]);
        Competitor::factory()->create([
            'market_id' => $market->id,
        ]);
        Mail::send(new SnapshotMonth($market));
        Mail::assertSent(function (SnapshotMonth $mail) use ($market) {
            $mail->build();

            return $mail->market->id === $market->id &&
                $mail->hasTo($market->locations()->first()->email()->first()->email) &&
                $mail->hasCc('kirk@thegreatescaperoom.com') &&
                $mail->hasBcc('digitalmgr@thegreatescaperoom.com');
        });
    }
}
