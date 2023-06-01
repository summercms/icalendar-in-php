<?php
/**
 * This file is part of the ICalendarOrg package
 *
 * (c) Bruce Wells
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source
 * code
 *
 * Contributed by Tanabi @ GitHub
 */
class LinefoldingTest extends \PHPUnit\Framework\TestCase
	{
	/**
	 * Test iCAL format with line folding (long lines)
	 */
	public function testLineFolding() : void
		{
		$sample = <<<EOS
BEGIN:VCALENDAR
PRODID;X-RICAL-TZSOURCE=TZINFO:-//Airbnb Inc//Hosting Calendar 0.8.8//EN
CALSCALE:GREGORIAN
VERSION:2.0
BEGIN:VEVENT
DTEND;VALUE=DATE:20230611
DTSTART;VALUE=DATE:20230514
UID:xxx@airbnb.com
DESCRIPTION:Reservation URL: https://www.airbnb.com/hosting/reservations/
 details/xxx\\nPhone Number (Last 4 Digits): 0000
SUMMARY:Reserved
END:VEVENT
END:VCALENDAR
EOS;

		$test = new \ICalendarOrg\ZCiCal($sample);

		// Make sure description 'reformed' correctly.
		$this->assertCount(1, $test->tree->child);

		$node = $test->tree->child[0];

		// Make sure all expected fields are there
		foreach (['DTEND', 'DTSTART', 'UID', 'DESCRIPTION', 'SUMMARY']
				 as $field) {
			$this->assertArrayHasKey($field, $node->data);
		}

		// Make sure description matches
		$this->assertEquals(
			\trim($node->data['DESCRIPTION']->values[0]),
			'Reservation URL: https://www.airbnb.com/hosting/reservations/details/xxx\nPhone Number (Last 4 Digits): 0000'
		);
		}
	}
