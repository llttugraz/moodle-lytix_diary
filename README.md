# lytix\_diary

This is a tool for students to document their studying habits and progress, and for teachers to check how actively students use it.


## JSON

### Teacher’s View

```
{
	// First recorded month.
	Start: <1–12>,

	// The first entry is today’s count; afterwards the number of entries per month
	// in reversed chronological order (latest first).
	Counts: [ <int>, … ]
}
```

#### Example

```
{
	Start: 9,
	Counts: [
		15, // today
		13, // Jan
		 9, // Dec
		 5, // Nov
		 0, // Oct
		 0, // Sep
	]
}
```
