<?php

declare(strict_types=1);

namespace PhpCfdi\XmlCancelacion\Tests\Unit\Capsules;

use DateTimeImmutable;
use PhpCfdi\XmlCancelacion\Capsules\Cancellation;
use PhpCfdi\XmlCancelacion\Models\CancelDocument;
use PhpCfdi\XmlCancelacion\Models\CancelDocuments;
use PhpCfdi\XmlCancelacion\Models\DocumentType;
use PhpCfdi\XmlCancelacion\Tests\TestCase;

final class CancellationTest extends TestCase
{
    public function testConstructAndGetParameters(): void
    {
        $rfc = 'LAN7008173R5';
        $documents = new CancelDocuments(
            CancelDocument::newWithErrorsUnrelated('12345678-1234-aaaa-1234-123456789001'),
            CancelDocument::newWithErrorsUnrelated('12345678-1234-aaaa-1234-123456789002'),
        );
        $date = new DateTimeImmutable('2022-01-13 14:15:16');
        $documentType = DocumentType::cfdi();

        $cancellation = new Cancellation($rfc, $documents, $date, $documentType);

        $this->assertSame($rfc, $cancellation->rfc());
        $this->assertSame($documents, $cancellation->documents());
        $this->assertSame($date, $cancellation->date());
        $this->assertSame($documentType, $cancellation->documentType());

        $this->assertTrue($cancellation->belongsToRfc($rfc));
        $this->assertFalse($cancellation->belongsToRfc('AAA010101AAA'));
    }

    public function testConstructWithoutDocumentType(): void
    {
        $rfc = 'LAN7008173R5';
        $documents = new CancelDocuments(
            CancelDocument::newWithErrorsUnrelated('12345678-1234-aaaa-1234-123456789001'),
            CancelDocument::newWithErrorsUnrelated('12345678-1234-aaaa-1234-123456789002'),
        );
        $date = new DateTimeImmutable('2022-01-13 14:15:16');
        $cancellation = new Cancellation($rfc, $documents, $date);
        $this->assertEquals(DocumentType::cfdi(), $cancellation->documentType());
    }

    public function testCount(): void
    {
        $date = new DateTimeImmutable('2022-01-13 14:15:16');
        $documents = new CancelDocuments(
            CancelDocument::newWithErrorsUnrelated('12345678-1234-aaaa-1234-123456789001'),
            CancelDocument::newWithErrorsUnrelated('12345678-1234-aaaa-1234-123456789002'),
        );
        $rfc = 'LAN7008173R5';
        $cancellation = new Cancellation($rfc, $documents, $date);
        $this->assertCount(2, $cancellation);
    }

    public function testExportToDocument(): void
    {
        // even when UUID using ampersand is not correct, it does not have to break our library
        $documents = new CancelDocuments(
            CancelDocument::newWithErrorsRelated(
                '11111111-2222-3333-4444-000000000001',
                '00000000-0000-0000-0000-000000000001'
            ),
            CancelDocument::newWithErrorsUnrelated('11111111-2222-3333-4444-000000000002'),
        );
        $dateTime = new DateTimeImmutable('2022-01-13 14:15:16');
        $cancellation = new Cancellation('LAN7008173R5', $documents, $dateTime);
        $expectedFile = $this->filePath('cancellation-document.xml');
        $this->assertXmlStringEqualsXmlFile($expectedFile, (string) $cancellation->exportToDocument()->saveXML());
    }

    public function testExportToDocumentWithRetention(): void
    {
        $documents = new CancelDocuments(
            CancelDocument::newWithErrorsRelated(
                '11111111-2222-3333-4444-000000000001',
                '00000000-0000-0000-0000-000000000001'
            ),
            CancelDocument::newWithErrorsUnrelated('11111111-2222-3333-4444-000000000002'),
        );
        $dateTime = new DateTimeImmutable('2022-01-13 14:15:16');
        $cancellation = new Cancellation('LAN7008173R5', $documents, $dateTime, DocumentType::retention());
        $expectedFile = $this->filePath('cancellation-retention-document.xml');
        $this->assertXmlStringEqualsXmlFile($expectedFile, (string) $cancellation->exportToDocument()->saveXML());
    }
}
