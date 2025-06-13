// Cache the formatters to avoid per-call instantiation overhead
const sarCurrencyFormatter = new Intl.NumberFormat("ar-SA", {
    style: "currency",
    currency: "SAR",
});

const arDateFormatter = new Intl.DateTimeFormat("ar-SA", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "numeric",
    minute: "numeric",
});

// Export optimized formatter functions
export const formatCurrency = (amount) => {
    if (amount == null || isNaN(amount)) return "";
    return sarCurrencyFormatter.format(Number(amount));
};

export const formatDate = (dateInput) => {
    if (!dateInput) return "";

    // Handle both Date objects and date strings
    const date = dateInput instanceof Date ? dateInput : new Date(dateInput);

    // Guard against invalid dates
    if (isNaN(date.getTime())) return "";

    return arDateFormatter.format(date);
};
