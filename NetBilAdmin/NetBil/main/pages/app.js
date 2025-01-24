import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet, Image, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';

const FenadoAIStudentBankingApp = () => {
  const [balance, setBalance] = useState(0);
  const [spending, setSpending] = useState(0);
  const [budgetCategories, setBudgetCategories] = useState([]);
  const [billSplit, setBillSplit] = useState([]);
  const [studentDiscounts, setStudentDiscounts] = useState([]);
  const [educationalFinance, setEducationalFinance] = useState([]);
  const [automatedSavings, setAutomatedSavings] = useState([]);
  const [expenseAnalytics, setExpenseAnalytics] = useState([]);

  useEffect(() => {
    // Fetch data from API
    fetch('https://api.fenado.ai/student-banking')
      .then(response => response.json())
      .then(data => {
        setBalance(data.balance);
        setSpending(data.spending);
        setBudgetCategories(data.budgetCategories);
        setBillSplit(data.billSplit);
        setStudentDiscounts(data.studentDiscounts);
        setEducationalFinance(data.educationalFinance);
        setAutomatedSavings(data.automatedSavings);
        setExpenseAnalytics(data.expenseAnalytics);
      });
  }, []);

  const handleBudgetCategory = (category, amount) => {

    // Update budget category
    setBudgetCategories(prevState => {
      const updatedCategories = [...prevState];
      const index = updatedCategories.findIndex(c => c.name === category);
      if (index !== -1) {
        updatedCategories[index].amount = amount;
      }
      return updatedCategories;
    });
  };

  const handleBillSplit = (bill, amount) => {
    // Update bill split
    setBillSplit(prevState => {
      const updatedBills = [...prevState];
      const index = updatedBills.findIndex(b => b.name === bill);
      if (index !== -1) {
        updatedBills[index].amount = amount;
      }
      return updatedBills;
    });
  };

  const handleStudentDiscount = (discount, amount) => {
    // Update student discount
    setStudentDiscounts(prevState => {
      const updatedDiscounts = [...prevState];
      const index = updatedDiscounts.findIndex(d => d.name === discount);
      if (index !== -1) {
        updatedDiscounts[index].amount = amount;
      }
      return updatedDiscounts;
    });
  };

  const handleEducationalFinance = (lesson, amount) => {
    // Update educational finance
    setEducationalFinance(prevState => {
      const updatedLessons = [...prevState];
      const index = updatedLessons.findIndex(l => l.name === lesson);
      if (index !== -1) {
        updatedLessons[index].amount = amount;
      }
      return updatedLessons;
    });
  };

  const handleAutomatedSavings = (goal, amount) => {
    // Update automated savings
    setAutomatedSavings(prevState => {
      const updatedGoals = [...prevState];
      const index = updatedGoals.findIndex(g => g.name === goal);
      if (index !== -1) {
        updatedGoals[index].amount = amount;
      }
      return updatedGoals;
    });
  };

  const handleExpenseAnalytics = (category, amount) => {
    // Update expense analytics
    setExpenseAnalytics(prevState => {
      const updatedCategories = [...prevState];
      const index = updatedCategories.findIndex(c => c.name === category);
      if (index !== -1) {
        updatedCategories[index].amount = amount;
      }
      return updatedCategories;
