import { test, expect, type Locator, type Page } from "@playwright/test";

interface fieldType {
    name: string;
    type: string;
    value: string;
    valueNotOk: string;
}

let requiredFields = [
    { name: "sub_fullname", type: "text", value: "test", valueNotOk: " " },
    {
        name: "sub_email",
        type: "email",
        value: "test@asd.com",
        valueNotOk: "any_wrong_email",
    },
    { name: "company_test", type: "text", value: "test", valueNotOk: " " },
] as fieldType[];

let stripeFields = [
    { name: "cardNumber", type: "text", value: "4242 4242 4242 4242" },
    { name: "cardExpiry", type: "email", value: "02 / 25" },
    { name: "billingName", type: "text", value: "Test Name" },
    { name: "cardCvc", type: "text", value: "123" },
] as fieldType[];

let invoiceFields = [
    { name: "company_name", type: "text", value: "BBSO" },
    { name: "company_cui", type: "text", value: "123475" },
    { name: "company_j", type: "text", value: "129/22/2022" },
    {
        name: "company_address",
        type: "text",
        value: "Strada Thurzó Sándor, Nr. 19, Oradea, România",
    },
    { name: "company_delegate", type: "text", value: "Sonea Cristian" },
] as fieldType[];

let pageUrl = "https://bbso.test/inregistrare/";
let stripeUrl = "stripe.com";

test.beforeEach(async ({ page }) => {
    await page.goto(pageUrl);
});

test.describe("Complet NOK", () => {
    test("direct submit", async ({ page }) => {
        // Click submit button
        await page.locator("button[type='submit']").click();

        // Get validation message for Fullname
        const fullnameInput = page.locator(
            "input#" + requiredFields[0].name
        ) as Locator;
        const validationMessage = (await fullnameInput.evaluate((element) => {
            const input = element as HTMLInputElement;
            return input.validationMessage;
        })) as string;

        expect(validationMessage).toContain("out this field");
    });

    test("test incorrect email", async ({ page }) => {
        await page
            .locator("#" + requiredFields[1].name)
            .fill(requiredFields[1].valueNotOk);

        // Click submit button
        await page.locator("button[type='submit']").click();

        // Get validation message for Fullname
        const testInput = page.locator(
            "input#" + requiredFields[1].name
        ) as Locator;
        const validationMessage = (await testInput.evaluate((element) => {
            const input = element as HTMLInputElement;
            return input.validationMessage;
        })) as string;

        expect(validationMessage).toContain("is missing an '@'");
    });
});

const fillFromArrayId = async (page, arrayVar) => {
    for (let i = 0; i < arrayVar.length; i++) {
        await page.locator("input#" + arrayVar[i].name).fill(arrayVar[i].value);
    }
};

const submitForm = async (page) => {
    // Click submit button
    await page.locator("button[type='submit']").click();

    await page.waitForTimeout(3000);
};

test.describe("Complete OK", () => {
    test("Stripe - submit + click cancel", async ({ page }) => {
        await page.waitForTimeout(500);

        await page.locator("input#payment_name_stripe_").click();
        await page.locator("input#payment_invoice_no_").click();
        // Fill in required fields
        await fillFromArrayId(page, requiredFields);

        await submitForm(page);
        await page.locator(".Link.Header-businessLink").click();

        expect(page.url()).toContain("?btdevReturn&session_id=");
    });

    // NO INVOICE
    test("No invoice - Stripe + submit", async ({ page }) => {
        await page.locator("input#payment_name_stripe_").click();
        await page.locator("input#payment_invoice_no_").click();
        await fillFromArrayId(page, requiredFields);

        await submitForm(page);
        await fillFromArrayId(page, stripeFields);
        await page.locator(".SubmitButton").click();
        await page.waitForTimeout(6000);

        expect(page.url()).toContain("?btdevSuccess&session_id=");
    });

    test("No invoice - OP + submit", async ({ page }) => {
        await page
            .locator("input[name='payment_name'][value='ordinPlata']")
            .click();
        await page.locator("input#payment_invoice_no_").click();
        // Fill in required fields
        await fillFromArrayId(page, requiredFields);

        await submitForm(page);

        expect(page.url()).toContain("inscriere-cu-succes");
    });

    // INVOICE
    test("Invoice - Stripe + submit", async ({ page }) => {
        await page.locator("input#payment_name_stripe_").click();
        await page.locator("input#payment_invoice_yes_").click();

        // Fill in required fields
        await page
            .locator("input#" + requiredFields[0].name)
            .fill(requiredFields[0].value);
        await page
            .locator("input#" + requiredFields[1].name)
            .fill(requiredFields[1].value);
        // Fill in company required fields
        await fillFromArrayId(page, invoiceFields);

        await submitForm(page);

        await fillFromArrayId(page, stripeFields);
        await page.locator(".SubmitButton").click();
        await page.waitForTimeout(6000);

        expect(page.url()).toContain("?btdevSuccess&session_id=");
    });

    test("Invoice - OP + submit", async ({ page }) => {
        await page
            .locator("input[name='payment_name'][value='ordinPlata']")
            .click();
        await page.locator("input#payment_invoice_yes_").click(); // Fill in required fields

        await page
            .locator("input#" + requiredFields[0].name)
            .fill(requiredFields[0].value);
        await page
            .locator("input#" + requiredFields[1].name)
            .fill(requiredFields[1].value);

        await page.waitForTimeout(500);
        // Fill in company required fields
        await fillFromArrayId(page, invoiceFields);
        await submitForm(page);

        expect(page.url()).toContain("inscriere-cu-succes");
    });
});
